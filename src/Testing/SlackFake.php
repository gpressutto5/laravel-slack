<?php

namespace Pressutto\LaravelSlack\Testing;

use Illuminate\Notifications\Messages\SlackMessage;
use PHPUnit\Framework\Assert as PHPUnit;
use Pressutto\LaravelSlack\Slack;

class SlackFake extends Slack
{
    /**
     * The sent messages.
     *
     * @var SlackMessage[]
     */
    private $sent = [];

    /**
     * Return the sent messages.
     *
     * @return SlackMessage[]
     */
    public function sentMessages(): array
    {
        return $this->sent;
    }

    /**
     * Assert that at least, or exactly(if $strict is true)
     * $count messages passed to $callable
     * returns true.
     *
     * @param  callable  $callback
     * @param  int  $count
     * @param  bool  $strict
     */
    public function assertSent(callable $callback, int $count = 1, bool $strict = false)
    {
        $sentCount = 0;

        foreach ($this->sentMessages() as $sentMessage) {
            if ($callback($sentMessage)) {
                $sentCount++;
            }
        }

        $passed = $strict ? $sentCount === $count : $sentCount >= $count;

        PHPUnit::assertTrue($passed,
            "The number of messages sent was {$sentCount} instead of {$count}");
    }

    /**
     * Asserts $count exactly messages were sent.
     *
     * @param  int  $count
     */
    public function assertSentCount(int $count)
    {
        $this->assertSent(function () {
            return true;
        }, $count, true);
    }

    protected function notify(SlackMessage $slackMessage)
    {
        $this->sent[] = $slackMessage;
    }
}
