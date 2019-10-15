<?php

namespace Pressutto\LaravelSlack;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\SlackMessage;
use Pressutto\LaravelSlack\Notifications\SimpleSlack;

class Slack
{
    /**
     * @var string[]
     */
    private $recipients = [];

    /**
     * @var AnonymousNotifiable
     */
    private $anonymousNotifiable;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $image;

    /**
     * @var array
     */
    private $config;

    public function __construct(array $config = [])
    {
        $config = $this->mergeConfig($config);
        $this->anonymousNotifiable = Notification::route('slack', $config['slack_webhook_url']);
        $this->recipients = [ $config['default_channel'] ];
        $this->from = $config['application_name'];
        $this->image = $config['application_image'];
        $this->config = $config;
    }

    /**
     * Use config variables specified by user
     * use default variables when not specified
     *
     * @param $config
     *
     * @return mixed
     */
    private function mergeConfig(array $config) : array
    {
        $defaultConfig = config('laravel-slack');

        foreach ($defaultConfig as $key => $value) {
            if (! array_key_exists($key, $config)) {
                $config[$key] = $value;
            }
        }

        return $config;
    }

    /**
     * Allows user specify webhook to use
     * for current instance.
     *
     * @param string $url
     *
     * @return $this
     */
    public function webhook(string $url) : self
    {
        $this->anonymousNotifiable = Notification::route('slack', $url);
        return $this;
    }

    /**
     * Set the recipients of the message.
     *
     * @param object|array|string $recipient
     *
     * @return $this
     */
    public function to($recipient): self
    {
        if ($recipient instanceof Collection) {
            $recipient = $recipient->all();
        }

        $recipients = is_array($recipient) ? $recipient : func_get_args();

        $this->recipients = array_map(
            function ($recipient) {
                if (is_object($recipient)) {
                    return $recipient->slack_channel;
                }

                return $recipient;
            }, $recipients
        );

        return $this;
    }

    /**
     * Send a new message.
     *
     * @param string|SlackMessage $message
     *
     * @return void
     */
    public function send($message)
    {
        $slackMessages = $this->getSlackMessageArray($message);

        foreach ($slackMessages as $slackMessage) {
            $this->notify($slackMessage);
        }

        $this->recipients = [$this->config['default_channel']];
    }

    protected function notify(SlackMessage $slackMessage)
    {
        $this->anonymousNotifiable->notify(new SimpleSlack($slackMessage));
    }

    /**
     * Send a new message.
     *
     * @param string|SlackMessage $message
     *
     * @return SlackMessage[]
     */
    protected function getSlackMessageArray($message): array
    {
        if ($message instanceof SlackMessage) {
            return [$message];
        }

        $slackMessageArray = [];
        $slackMessage = (new SlackMessage())->content($message);

        if ($this->from) {
            $slackMessage->from($this->from);
        }

        if ($this->image) {
            $slackMessage->image($this->image);
        }

        foreach ($this->recipients as $recipient) {
            $messageClone = clone $slackMessage;
            $slackMessageArray[] = $messageClone->to($recipient);
        }

        return $slackMessageArray;
    }
}
