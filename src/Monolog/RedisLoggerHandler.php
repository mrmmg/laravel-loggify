<?php

namespace Mrmmg\LaravelLoggify\Monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class RedisLoggerHandler extends AbstractProcessingHandler
{
    private string $uuid;
    private $redis;

    public function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        $this->redis = app('redis')
            ->connection('default')
            ->client();

        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        $record = $record['formatted'];

        $this->uuid = $record['uuid'];

        foreach ($record['tags'] as $tag){
            $tag = "ids_tag::$tag";

            $this->redis->rpush($tag, $this->uuid);
            $this->redis->expire($tag, 86400);

            $this->redis->zincrby("loggify::tags", 1, $tag);

            //todo: if the config limit set implement this method
            //$this->redis->ltrim($tag, -1000, -1);
        }

        $this->redis->setex(
            $this->uuid,
            86400,
            $record['formatted']
        );
    }
}
