<?php

use Illuminate\Support\Facades\Route;
use Mrmmg\LaravelRedisLoggify\Http\Controllers\LoggifyController;

Route::group(['prefix' => 'loggify'], function (){
    Route::get('/{tag?}', [LoggifyController::class, 'index']);
});

