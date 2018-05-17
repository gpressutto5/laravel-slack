<?php

namespace Pressutto\LaravelSlack;

use Illuminate\Support\Collection;
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

    public function __construct(array $config)
    {
        $this->anonymousNotifiable = \Notification::route('slack', $config['slack_webhook_url']);
        $this->recipients = [$config['default_channel']];
        $this->from = $config['application_name'];
        $this->image = $config['application_image'];
    }

    /**
     * Set the recipients of the message.
     *
     * @param  object|array|string $recipient
     *
     * @return $this
     */
    public function to($recipient): self
    {
        if ($recipient instanceof Collection) {
            $recipient = $recipient->toArray();
        }

        $this->recipients = is_array($recipient) ? $recipient : func_get_args();

        return $this;
    }

    /**
     * Send a new message using a view.
     *
     * @param string|SlackMessage $message
     *
     * @return void
     */
    public function send($message)
    {
        if ($message instanceof SlackMessage) {
            $this->anonymousNotifiable->notify(new SimpleSlack($message));
            return;
        }

        $slackMessage = (new SlackMessage())->content($message);

        if ($this->from) {
            $slackMessage->from($this->from);
        }

        if ($this->image) {
            $slackMessage->image($this->image);
        }

        foreach ($this->recipients as $recipient) {
            $this->anonymousNotifiable->notify(
                new SimpleSlack(
                    $slackMessage->to($recipient)
                )
            );
        }
    }
}
