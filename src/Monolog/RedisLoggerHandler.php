<?php

namespace Mrmmg\LaravelLoggify\Monolog;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Mrmmg\LaravelLoggify\Helpers\LoggifyRedis;

class RedisLoggerHandler extends AbstractProcessingHandler
{
    private string $uuid;
    private $redis;
    private $reids_expire_time;

    public function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        $this->redis = LoggifyRedis::redisConnection();

        $this->reids_expire_time = config('loggify.log_expire_seconds', 60*60);

        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        $record = $record['formatted'];

        $this->uuid = $record['uuid'];

        $overflow_ids = [];

        $max_tag_items = abs(config('loggify.max_tag_items', false));
        $is_capped_mode = (bool)$max_tag_items;

        $tag_expiration = abs(config('loggify.log_tag_expire_seconds', false));
        $expirable_tag = (bool)$tag_expiration;

        foreach ($record['tags'] as $tag){
            $tag = "ids_tag::$tag";

            $this->redis->rpush($tag, $this->uuid);

            if($is_capped_mode){
                $overflow_ids[] = $this->redis
                    ->lRange($tag, 0, ($max_tag_items + 1) * (-1));

                $this->redis
                    ->lTrim($tag, (-1*$max_tag_items), -1);
            }

            if($expirable_tag){
                $this->redis->expire($tag, $tag_expiration);
            }
        }

        $this->redis->setex(
            $this->uuid,
            $this->reids_expire_time,
            $record['formatted']
        );

        $this->removeOverFlowIdsFromDatabase($overflow_ids);
    }

    /**
     * @param array $overflow_ids
     * @return void
     */
    private function removeOverFlowIdsFromDatabase(array $overflow_ids): void
    {
        $overflow_ids = $this->flatAndFilterOverFlowIds($overflow_ids);

        if (!empty($overflow_ids)) {
            Redis::transaction(function () use ($overflow_ids) {
                foreach ($overflow_ids as $id) {
                    $this->redis->del($id);
                }
            });
        }
    }

    /**
     * @param array $overflow_ids
     * @return array
     */
    private function flatAndFilterOverFlowIds(array $overflow_ids): array
    {
        $overflow_ids = Arr::flatten($overflow_ids);
        return array_filter($overflow_ids);
    }
}
