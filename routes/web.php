<?php

use Illuminate\Support\Facades\Route;
use Mrmmg\LaravelLoggify\Http\Controllers\LoggifyController;

Route::group([
    'prefix' => 'loggify',
    'middleware' => [
        'loggify-web'
    ],
    'as' => 'loggify.'
], function (){
    Route::get('/{tag?}', [LoggifyController::class, 'index'])
        ->name('view_tag');
});

