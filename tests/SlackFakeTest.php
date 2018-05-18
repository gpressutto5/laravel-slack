<?php

namespace Tests;

use Pressutto\LaravelSlack\Facades\Slack;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\Constraint\ExceptionMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Pressutto\LaravelSlack\Notifications\SimpleSlack;

class SlackFakeTest extends TestCase
{
    public function testFakeSlackWontSendMessage()
    {
        $notification = Notification::fake();

        Slack::fake();
        \Slack::send('FAKE');

        $notification->assertSentTo(new AnonymousNotifiable(), SimpleSlack::class, 0);
    }

    public function testAssertSentCount()
    {
        $fake = Slack::fake();

        try {
            $fake->assertSentCount(1);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertThat($e, new ExceptionMessage('The number of messages sent was 0 instead of 1'));
        }

        \Slack::send('FAKE');

        $fake->assertSentCount(1);
    }

    public function testAssertSentString()
    {
        $fake = Slack::fake();

        \Slack::to('test')->send('fake');
        \Slack::to('testa')->send('zxdzda');

        try {
            $fake->assertSent(function (SlackMessage $message) {
                return $message->content === 'maybe not fake?';
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertThat($e, new ExceptionMessage('The number of messages sent was 0 instead of 1'));
        }

        $fake->assertSent(function (SlackMessage $message) {
            return $message->content === 'fake';
        });
    }

    public function testAssertSentSlackMessage()
    {
        $fake = Slack::fake();
        $messageSent = (new SlackMessage())->to('@right_user')->content('fake SlackMessage');
        $messageNotSent = (new SlackMessage())->to('@wrong_user')->content('fake SlackMessage');

        \Slack::send($messageSent);

        try {
            $fake->assertSent(function (SlackMessage $message) use ($messageNotSent) {
                return $message === $messageNotSent;
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertThat($e,
                new ExceptionMessage('The number of messages sent was 0 instead of 1'));
        }

        $fake->assertSent(function (SlackMessage $message) use ($messageSent) {
            return $message === $messageSent;
        });
    }
}
