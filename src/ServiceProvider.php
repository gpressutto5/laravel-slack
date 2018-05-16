<?php

namespace Pressutto\LaravelSlack;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__.'/../config/laravel-slack.php';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, 'laravel-slack');

        $this->app->singleton(Slack::class, function ($app) {
            $slackWebhookUrl = $app['config']->get('laravel-slack.slack_webhook_url');
            $anonymousNotifiable = \Notification::route('slack', $slackWebhookUrl);

            return new Slack($anonymousNotifiable);
        });

        $this->app->alias(Slack::class, 'slack');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([self::CONFIG_PATH => config_path('laravel-slack.php')], 'config');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Slack::class];
    }
}
