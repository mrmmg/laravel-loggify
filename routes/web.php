<?php

use Illuminate\Support\Facades\Route;
use Mrmmg\LaravelLoggify\Http\Controllers\LoggifyController;

Route::group(['prefix' => 'loggify'], function (){
    Route::get('/{tag?}', [LoggifyController::class, 'index'])
        ->name('loggify.view_tag');
});

