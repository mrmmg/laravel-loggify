<?php

namespace Mrmmg\LaravelLoggify;

use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;

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

    public static function getTagLogs($tag, $limit = null): array
    {
        $tag = "ids_tag::$tag";

        $members = self::redisConnection()->lrange($tag, 0, -1);

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
            ->keyBy(function ($item) use (&$members) {
                $pattern = '/^.*' . preg_quote($item['uuid'], '/') . '$/';
                $regexResult = preg_grep($pattern, $members);
                return "loggify_" . Arr::first($regexResult);
            })
            ->reverse()
            ->toArray();

        self::removeExpiredLogsFromTags($tag, $expired_logs);

        return $logs;
    }

    public static function getInformation(): array
    {
        $info = self::redisConnection()->info();

        $tags = self::redisConnection()->zrange("loggify::tags", 0, -1, ['WITHSCORES' => true ]);

        $tags = Arr::keyBy($tags, function ($value, $key){
            return str_replace("ids_tag::", "", $key);
        });

        uasort($tags, fn($a,$b) => $b <=> $a);

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

            'tags' => $tags
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
}
