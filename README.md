## About Laravel Slack

This is a fork from `https://github.com/gpressutto5/laravel-slack` to support Laravel 6. To read the docs visit the [original repository](https://github.com/gpressutto5/laravel-slack).

## How to use this fork
1. Open `composer.json`
1. Edit/Add a `repositories` block, with the following:

        "repositories": [
            {
              "type": "vcs",
              "url": "https://github.com/dellow/laravel-slack"
            }
        ]
1. Update the `require` block with the following:

        "require": {
            "gpressutto5/laravel-slack": "dev-laravel-6"
        }
        
> You must reference the original upstream package: `gpressutto5/laravel-slack` but the version but reference this fork: `dev-laravel-6`.
