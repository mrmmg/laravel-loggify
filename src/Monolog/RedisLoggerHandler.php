<?php

namespace Mrmmg\LaravelLoggify\Monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Mrmmg\LaravelLoggify\LoggifyHelper;

class RedisLoggerHandler extends AbstractProcessingHandler
{
    private string $uuid;
    private $redis;
    private $reids_expire_time;

    public function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        $this->redis = LoggifyHelper::redisConnection();

        $this->reids_expire_time = config('laravelLoggify.log_expire_seconds', 60*60);

        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        $record = $record['formatted'];

        $this->uuid = $record['uuid'];

        foreach ($record['tags'] as $tag){
            $tag = "ids_tag::$tag";

            $this->redis->rpush($tag, $this->uuid);
            $this->redis->expire($tag, $this->reids_expire_time);

            $this->redis->zincrby("loggify::tags", 1, $tag);

            //todo: if the config limit set implement this method
            //$this->redis->ltrim($tag, -1000, -1);
        }

        $this->redis->setex(
            $this->uuid,
            $this->reids_expire_time,
            $record['formatted']
        );
    }
}
