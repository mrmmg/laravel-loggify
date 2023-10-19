<?php

namespace Mrmmg\LaravelLoggify\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Mrmmg\LaravelLoggify\LoggifyHelper;

class LoggifyController extends BaseController
{
    public function index(Request $request, $tag = null)
    {
        $tag = is_null($tag) ? "ALL_LOGS" : $tag;

        $limit = $request->limit ? (int)$request->limit : null;

        $logs = LoggifyHelper::getTagLogs($tag, $limit);

        $information = LoggifyHelper::getInformation();

        return view('loggify::log', compact('logs', 'information'));
    }
}
