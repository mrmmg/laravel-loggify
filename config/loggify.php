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

    'logging' => [
        'channels' => [
            'loggify' => [
                'driver'  => 'custom',
                'via' => \Mrmmg\LaravelLoggify\Monolog\RedisLogger::class,
                'ignore_exceptions' => false
            ]
        ]
    ],

    /*
   |--------------------------------------------------------------------------
   | Log Expire Time
   |--------------------------------------------------------------------------
   |
   | In redis we stored items with expiration time.
   | This value contains the maximum number of seconds that a redis item can remain in the database.
   */

    'log_expire_seconds' => 24*60*60,

    //    'max_tag_items' => 10,

    'guard' => ['web'],
];
