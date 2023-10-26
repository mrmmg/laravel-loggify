<?php

namespace Mrmmg\LaravelLoggify\Http\Middleware;

use Mrmmg\LaravelLoggify\Helpers\LoggifyAuth;

class Authorize
{
    public function handle($request, $next)
    {
        if(!LoggifyAuth::check($request)) {
            abort(403);
        }

        return $next($request);
    }
}
