<?php

namespace Mrmmg\LaravelLoggify\Monolog;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Monolog\Formatter\FormatterInterface;
use Monolog\Logger;

class RedisLoggerFormatter implements FormatterInterface
{
    public function format(array $record)
    {
        $record = $this->normalize($record);

        $record['formatted'] = serialize($record);

        return $record;
    }

    public function formatBatch(array $records)
    {
        foreach ($records as $key => $record) {
            $records[$key] = $this->format($record);
        }

        return $records;
    }

    private function normalize(array $record)
    {
        $record['uuid'] = Str::orderedUuid()->toString();

        $record['datetime'] = Carbon::now();

        $record['tags'] = [
            'ALL_LOGS',
            "LOG_TYPE_$record[level_name]"
        ];

        $record['trace'] = NULL;

        if (in_array($record['level'], [
            Logger::DEBUG,
            Logger::ALERT,
            Logger::CRITICAL,
            Logger::EMERGENCY,
            Logger::ERROR,
            Logger::WARNING
        ], true)) {
            $record['trace'] = $this->getDebugTrace();
        }

        if (isset($record['context']['tags'])) {
            $tags = (array)$record['context']['tags'];
            $tags = array_unique($tags);
            $tags = array_map("strtoupper", $tags);

            $record['tags'] = array_merge($record['tags'], $tags);
            unset($record['context']['tags']);
        }

        if (isset($record['context']['extra'])) {
            $record['extra'] = (array)$record['context']['extra'];
            unset($record['context']['extra']);
        }

        return $record;
    }

    private function getDebugTrace(): array
    {
        $traces = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        return $this->sanitizeTraceLog($traces);
    }

    /**
     * @param array $traces
     * @return array
     */
    private function sanitizeTraceLog(array $traces): array
    {
        $removeFromDebugBackTrace = [
            'Mrmmg\LaravelLoggify',
            'Monolog',
            \Illuminate\Log\Logger::class
        ];

        foreach ($traces as $index => $trace) {
            if (array_key_exists('class', $trace)
                &&
                Str::contains($trace['class'], $removeFromDebugBackTrace)
            ) {
                unset($traces[$index]);
            }
        }

        //reset array keys
        return array_values($traces);
    }
}
