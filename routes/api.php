<?php

use App\Http\Controllers\Api\ChatBotController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'chat', 'as' => 'api.chat.'], static function () {
    Route::get('embed/{uuid}', [ChatBotController::class, 'embed'])->name('embed');
    Route::get('/', [ChatBotController::class, 'result'])->name('result');
    Route::post('/', [ChatBotController::class, 'request'])->name('message');
    // Handle CORS preflight OPTIONS request
    Route::options('/', static fn() => response('', 200));
});
