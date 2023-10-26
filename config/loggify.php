<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    |
    | Since this package uses Redis to store logs,
    | please enter the relevant information in this section.
    | The structure of this part of the configuration is similar to the config/database.php file.
    | Remember that by default this package stores its information in Redis database number 10.
    */

    'database' => [
        'redis' => [
            'client' => env('REDIS_CLIENT', 'phpredis'),

            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => '10',
        ]
    ],

    /*
   |--------------------------------------------------------------------------
   | Log Expiration
   |--------------------------------------------------------------------------
   |
   | In redis we stored items with expiration time.
   | This value contains the maximum number of seconds that a redis item can remain in the database.
   */

    'log_expire_seconds' => 24*60*60,

    /*
   |--------------------------------------------------------------------------
   | Tag Logs Limit
   |--------------------------------------------------------------------------
   |
   | Possible Values Are: an integer or false
   |
   | If set to 'false,' the tags can hold an infinite number of items.
   | By specifying a positive integer, Loggify will store logs in capped mode.
   | When the log tag limit is reached, it will remove tags up to the 'max_tag_items' value.
   */

    'max_tag_items' => false,

    'guard' => ['web'],
];
