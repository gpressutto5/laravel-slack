<?php

namespace Tests;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Support\Facades\Notification;
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

    public function testSendMessageToAChannelWithSpecifiedWebHook()
    {
        $notification = Notification::fake();

        \Slack::to('#random')->webhook('https://hooks.slack.com/services/XXXXXXXXX/XXXXXXXXX/XXXXXXXXXXXXXXXXXXXXXXXX')->send('RANDOM');

        $notification->assertSentTo(new AnonymousNotifiable(), SimpleSlack::class, 1);
        $slackMessageSent = $notification->sent(new AnonymousNotifiable(), SimpleSlack::class)->first()->toSlack();
        $this->assertEquals('#random', $slackMessageSent->channel);
        $this->assertEquals('RANDOM', $slackMessageSent->content);
    }

    public function testSendMessageToAUserWithSpecifiedConfig()
    {
        $notification = Notification::fake();

        \Pressutto\LaravelSlack\Facades\Slack::to('#fashion')->webhook('https://hooks.slack.com/services/XXXXXXXXX/XXXXXXXXX/XXXXXXXXXXXXXXXXXXXXXXXX')->send('RANDOM');

        $notification->assertSentTo(new AnonymousNotifiable(), SimpleSlack::class, 1);
        $slackMessageSent = $notification->sent(new AnonymousNotifiable(), SimpleSlack::class)->first()->toSlack();
        $this->assertEquals('#fashion', $slackMessageSent->channel);
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

    public function testSendMessageToMultipleUsers()
    {
        $notification = Notification::fake();

        \Slack::to('@user1', '@user2')->send('USER');

        $notification->assertSentTo(new AnonymousNotifiable(), SimpleSlack::class, 2);
        $sentNotifications = $notification->sent(new AnonymousNotifiable(), SimpleSlack::class);
        $slackMessageSent = $sentNotifications->first()->toSlack();
        $this->assertEquals('@user1', $slackMessageSent->channel);
        $this->assertEquals('USER', $slackMessageSent->content);
        $slackMessageSent = $sentNotifications->last()->toSlack();
        $this->assertEquals('@user2', $slackMessageSent->channel);
        $this->assertEquals('USER', $slackMessageSent->content);
    }

    public function testSendMessageToTheDefaultSlackChannel()
    {
        $notification = Notification::fake();

        $this->app['config']->set('laravel-slack.default_channel', null);

        \Slack::send('Message');

        $notification->assertSentTo(new AnonymousNotifiable(), SimpleSlack::class, 1);
        $slackMessageSent = $notification->sent(new AnonymousNotifiable(), SimpleSlack::class)->first()->toSlack();
        $this->assertEquals(null, $slackMessageSent->channel);
        $this->assertEquals('Message', $slackMessageSent->content);
    }

    public function testSendMessageToTheDefaultConfigChannel()
    {
        $notification = Notification::fake();

        \Slack::send('Message');

        $notification->assertSentTo(new AnonymousNotifiable(), SimpleSlack::class, 1);
        $slackMessageSent = $notification->sent(new AnonymousNotifiable(), SimpleSlack::class)->first()->toSlack();
        $this->assertEquals('#general', $slackMessageSent->channel);
        $this->assertEquals('Message', $slackMessageSent->content);
    }

    public function testSendMessageWithTheDefaultSlackUsername()
    {
        $notification = Notification::fake();

        $this->app['config']->set('laravel-slack.application_name', 'Slacker');

        \Slack::send('Message');

        $notification->assertSentTo(new AnonymousNotifiable(), SimpleSlack::class, 1);
        $slackMessageSent = $notification->sent(new AnonymousNotifiable(), SimpleSlack::class)->first()->toSlack();
        $this->assertEquals('Slacker', $slackMessageSent->username);
        $this->assertEquals('Message', $slackMessageSent->content);
    }

    public function testSendMessageWithTheDefaultConfigUsername()
    {
        $notification = Notification::fake();

        \Slack::send('Message');

        $notification->assertSentTo(new AnonymousNotifiable(), SimpleSlack::class, 1);
        $slackMessageSent = $notification->sent(new AnonymousNotifiable(), SimpleSlack::class)->first()->toSlack();
        $this->assertEquals(null, $slackMessageSent->username);
        $this->assertEquals('Message', $slackMessageSent->content);
    }

    public function testSendMessageWithTheDefaultSlackImage()
    {
        $notification = Notification::fake();

        $this->app['config']->set('laravel-slack.application_image', 'https://laravel.com/favicon.png');

        \Slack::send('Message');

        $notification->assertSentTo(new AnonymousNotifiable(), SimpleSlack::class, 1);
        $slackMessageSent = $notification->sent(new AnonymousNotifiable(), SimpleSlack::class)->first()->toSlack();
        $this->assertEquals('https://laravel.com/favicon.png', $slackMessageSent->image);
        $this->assertEquals('Message', $slackMessageSent->content);
    }

    public function testSendMessageWithTheDefaultConfigImage()
    {
        $notification = Notification::fake();

        \Slack::send('Message');

        $notification->assertSentTo(new AnonymousNotifiable(), SimpleSlack::class, 1);
        $slackMessageSent = $notification->sent(new AnonymousNotifiable(), SimpleSlack::class)->first()->toSlack();
        $this->assertEquals(null, $slackMessageSent->image);
        $this->assertEquals('Message', $slackMessageSent->content);
    }

    public function testSendSlackMessage()
    {
        $notification = Notification::fake();
        $message = (new SlackMessage())->to('#custom-channel')->content('Sending a SlackMessage');

        \Slack::send($message);

        $notification->assertSentTo(new AnonymousNotifiable(), SimpleSlack::class, 1);
        $slackMessageSent = $notification->sent(new AnonymousNotifiable(), SimpleSlack::class)->first()->toSlack();
        $this->assertEquals('#custom-channel', $slackMessageSent->channel);
        $this->assertEquals('Sending a SlackMessage', $slackMessageSent->content);
    }
}
