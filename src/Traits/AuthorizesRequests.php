<?php

namespace Mrmmg\LaravelLoggify\Traits;

trait AuthorizesRequests
{
    // This code copied from laravel telescope package :)

    /**
     * The callback that should be used to authenticate Loggify users.
     *
     * @var \Closure
     */
    public static $authUsing;

    /**
     * Register the Loggify authentication callback.
     *
     * @param  \Closure  $callback
     * @return static
     */
    public static function auth($callback)
    {
        static::$authUsing = $callback;

        return new static;
    }

    /**
     * Determine if the given request can access the Loggify dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function check($request)
    {
        return (static::$authUsing ?: function () {
            return true;
        })($request);
    }
}
