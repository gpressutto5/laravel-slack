<?php

namespace Pressutto\LaravelSlack;

use Illuminate\Support\Collection;

class Slack
{
    /**
     * @var string[]
     */
    private $recipients = [];

    /**
     * @var string
     */
    private $slackWebhookUrl;

    public function __construct(string $slackWebhookUrl)
    {
        $this->slackWebhookUrl = $slackWebhookUrl;
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
