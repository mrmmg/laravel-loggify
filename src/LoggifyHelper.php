<?php

namespace Mrmmg\LaravelLoggify;

use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;

class LoggifyHelper
{
    public static function redisConnection()
    {
        $config = app()->make('config')->get('database.redis', []);
        $driver = Arr::pull($config, 'client', 'phpredis');

        return (
            new RedisManager(app(), $driver, $config)
        )
            ->connection('loggify')
            ->client();
    }
}
