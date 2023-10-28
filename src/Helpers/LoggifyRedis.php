<?php

namespace Mrmmg\LaravelLoggify\Helpers;

use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;

class LoggifyRedis
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

    public static function getTagLogs($tag, $limit = null): array
    {
        /**
         * Todo: Implement pagination in lrange command
         * the lrange accepts two parameter, start and end
         * in each page by passing the start and end you can control each page items,
         * for example with a tag by 10 items and limit = 3 the lrange like this
         * page1: lrange key 0 2
         * page2: lrange key 3 5
         * page3: lrange key 6 8
         * page4: lrange key 9 11
         *
         * in page 2 and more the start is last_page_end + 1 and the end is (page_number * limit) - 1
         *
         * total page can be calculated with:
         * llen key => total elements in key / limit (HALF ROUND MAX)
         *
         * previous page can be calculated with:
         *
         *
         * current page can be calculated with:
         * (end + 1) / limit
         *
         * next page can be calculated with:
         *
         */
        $tag = "ids_tag::$tag";

        $redis_lrange_limit = !is_null($limit) ? $limit : -1;

        $members = self::redisConnection()->lrange($tag, 0, $redis_lrange_limit);

        if(empty($members)) {
            return [];
        }

        $logs = self::redisConnection()->mget($members);

        $logs = collect(array_combine($members, $logs));

        $expired_logs = [];

        $logs = $logs
            ->filter(function ($item, $key) use (&$expired_logs) {
                if (is_null($item)) {
                    $expired_logs[] = $key;
                    return "";
                }

                return true;
            })
            ->map(fn($item) => unserialize($item))
            ->keyBy(function ($item) {
                return "loggify_$item[uuid]";
            })
            ->reverse()
            ->toArray();

        self::removeExpiredLogsFromTags($tag, $expired_logs);

        return $logs;
    }

    public static function getInformation(): array
    {
        $info = self::redisConnection()->info();

        $tags_count = self::getTagsCount();

        $db_number = config('loggify.database.redis.database');

        $keyspace_available = array_key_exists("db$db_number", $info['Keyspace']);

        return [
            'db' => [
                'total_keys' => $keyspace_available ? $info['Keyspace']["db$db_number"]['keys'] : 0,
                'expirable_keys' => $keyspace_available ? $info['Keyspace']["db$db_number"]['expires'] : 0,
                'used_memory' => $info['Memory']['used_memory_human'],
                'used_memory_peak' => $info['Memory']['used_memory_peak_human'],
                'redis_version' => $info['Server']['redis_version']
            ],

            'tags' => $tags_count
        ];
    }

    private static function removeExpiredLogsFromTags(string $tag, array $expired_logs){
        if (!empty($expired_logs)) {
            Redis::transaction(function () use ($tag, $expired_logs){
                foreach ($expired_logs as $expired_log){
                    self::redisConnection()->lrem($tag, 0, $expired_log);
                }
            });
        }
    }

    /**
     * @return array
     */
    private static function getTagsCount(): array
    {
        $tags = self::redisConnection()->keys('ids_tag::*');

        $redis_prefix = config('database.redis.options.prefix');

        $tags = array_map(function ($tag) use ($redis_prefix) {
            return str_replace($redis_prefix, "", $tag);
        }, $tags);

        $tags_count = [];

        Redis::transaction(function () use ($tags, &$tags_count) {
            foreach ($tags as $tag) {
                $count = self::redisConnection()->lLen($tag);
                $tag = str_replace("ids_tag::", "", $tag);
                $tags_count[$tag] = $count;
            }
        });

        uasort($tags_count, fn($a, $b) => $b <=> $a);
        return $tags_count;
    }
}
