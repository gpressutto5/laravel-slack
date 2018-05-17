<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Slack Webhook URL
    |--------------------------------------------------------------------------
    |
    | Incoming Webhooks are a simple way to post messages from external sources
    | into Slack. You can read more about it and how to create yours
    | here: https://api.slack.com/incoming-webhooks
    |
    */

    'slack_webhook_url' => env('SLACK_WEBHOOK_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Channel
    |--------------------------------------------------------------------------
    |
    | If no recipient is specified the message will delivered to this channel.
    | You can set a default user by using '@' instead of '#'
    |
    */

    'default_channel' => '#general',

];
