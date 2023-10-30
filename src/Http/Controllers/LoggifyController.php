<?php

namespace Mrmmg\LaravelLoggify\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Mrmmg\LaravelLoggify\Helpers\LoggifyRedis;

class LoggifyController extends BaseController
{
    public function index(Request $request, $tag = null)
    {
        $tag = is_null($tag) ? "ALL_LOGS" : $tag;

        $limit = $request->limit ? (int)$request->limit : config('loggify.per_page_item_limit', 100);
        $page = $request->page ? (int)$request->page : 1;

        $result = LoggifyRedis::getTagLogs($tag, $limit, $page);

        $information = LoggifyRedis::getInformation();

        return view('loggify::log', compact('result', 'information'));
    }
}
