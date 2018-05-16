<?php

namespace Tests;

use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use Pressutto\LaravelSlack\Notifications\SimpleSlack;

class SendMessageTest extends TestCase
{
    public function testSendMessageToAChannel()
    {
        $notification = Notification::fake();

        \Slack::to('#random')->send('RANDOM');

        $notification->assertSentTo(new AnonymousNotifiable(), SimpleSlack::class, 1);
        $slackMessageSent = $notification->sent(new AnonymousNotifiable(), SimpleSlack::class)->first()->toSlack();
        $this->assertEquals('#random', $slackMessageSent->channel);
        $this->assertEquals('RANDOM', $slackMessageSent->content);
    }

    public function testSendMessageToAnUser()
    {
        $notification = Notification::fake();

        \Slack::to('@user')->send('USER');

        $notification->assertSentTo(new AnonymousNotifiable(), SimpleSlack::class, 1);
        $slackMessageSent = $notification->sent(new AnonymousNotifiable(), SimpleSlack::class)->first()->toSlack();
        $this->assertEquals('@user', $slackMessageSent->channel);
        $this->assertEquals('USER', $slackMessageSent->content);
    }
}
