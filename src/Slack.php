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

    public function __construct(AnonymousNotifiable $anonymousNotifiable)
    {
        $this->anonymousNotifiable = $anonymousNotifiable;
    }

    /**
     * Set the recipients of the message.
     *
     * @param  object|array|string $recipient
     *
     * @return $this
     */
    public function to($recipient)
    {
        if ($recipient instanceof Collection) {
            $recipient = $recipient->toArray();
        }

        $recipients = is_array($recipient) ? $recipient : func_get_args();

        return $this->setRecipient($recipients);
    }

    /**
     * Send a new message using a view.
     *
     * @param $message
     *
     * @return void
     */
    public function send(string $message)
    {
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

    /**
     * Set the recipients of the message.
     * All recipients are stored internally as ['#general', '@user', '@another_user'].
     *
     * @param  object|array|string $recipient
     *
     * @return $this
     */
    private function setRecipient($recipient)
    {
        $this->recipients = $recipient;

        return $this;
    }
}
