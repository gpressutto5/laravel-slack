<?php

namespace Pressutto\LaravelSlack\Notifications;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SimpleSlack extends Notification
{
    /**
     * @var SlackMessage
     */
    protected $message;

    /**
     * SimpleSlack constructor.
     *
     * @param SlackMessage $message
     */
    public function __construct(SlackMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     * This channel will always be slack.
     *
     * @return array
     */
    public function via()
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @return SlackMessage
     */
    public function toSlack()
    {
        return $this->message;
    }
}
