<?php
namespace Mrmmg\LaravelLoggify\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @class Loggify
 *
 * @method static \Mrmmg\LaravelLoggify\Logger\RedisLogger getByTag(string $message) the log message
 *
 */
class Loggify extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'loggify';
    }
}
