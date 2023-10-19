<?php

namespace Mrmmg\LaravelLoggify\Monolog;

use Monolog\Logger;

class RedisLogger
{
    public function __invoke(array $config)
    {
        $handler = new RedisLoggerHandler();
        $handler->setFormatter(new RedisLoggerFormatter());

        return new Logger(
            name: 'loggify',
            handlers: [$handler],
            processors: []
        );
    }
}
