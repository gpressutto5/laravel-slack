<?php

namespace Pressutto\LaravelSlack\Facades;

use Illuminate\Support\Facades\Facade;
use Pressutto\LaravelSlack\Slack as Slacker;
use Pressutto\LaravelSlack\Testing\SlackFake;

class Slack extends Facade
{
    /**
     * Replace the bound instance with a fake.
     *
     * @return SlackFake
     */
    public static function fake(): SlackFake
    {
        static::swap($fake = new SlackFake(config('laravel-slack')));

        return $fake;
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Slacker::class;
    }
}
