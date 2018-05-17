<?php

namespace Tests;

use Pressutto\LaravelSlack\Facades\Slack;
use Pressutto\LaravelSlack\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Slack' => Slack::class,
        ];
    }
}
