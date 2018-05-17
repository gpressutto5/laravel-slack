<?php

namespace Pressutto\LaravelSlack\Facades;

use Illuminate\Support\Facades\Facade;
use Pressutto\LaravelSlack\Slack as Slacker;

class Slack extends Facade
{
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
