<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DevotionController;
use App\Http\Controllers\Api\DeviceController;

/*
|--------------------------------------------------------------------------
| Devotion API
|--------------------------------------------------------------------------
*/
Route::middleware('throttle:api-public')
    ->prefix('devotion')
    ->group(function () {

        Route::get('/today',
            [DevotionController::class, 'today']
        );

        Route::get('/{date}',
            [DevotionController::class, 'show']
        );

    });

Route::middleware('throttle:api-public')
    ->get('/devotions',
        [DevotionController::class, 'index']
    );

/*
|--------------------------------------------------------------------------
| Device API
|--------------------------------------------------------------------------
*/
Route::prefix('device')
    ->group(function () {

        Route::post('/register',
            [DeviceController::class, 'register']
        )
        ->middleware('throttle:device-register');

        Route::post('/ping',
            [DeviceController::class, 'ping']
        )
        ->middleware('throttle:device-ping');

    });