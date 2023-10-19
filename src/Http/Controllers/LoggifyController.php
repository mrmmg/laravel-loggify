<?php

namespace Mrmmg\LaravelLoggify\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class LoggifyController extends BaseController
{
    public function index(Request $request, $tag = null)
    {
        $default_tag = is_null($tag) ? "ALL_LOGS" : $tag;

        $limit = $request->limit ? (int)$request->limit : 2000;

        $logs = [];

        return view('laravelLoggify::show_tag_logs', compact('logs'));
    }
}
