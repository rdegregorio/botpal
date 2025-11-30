<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ChatConfigsController;
use App\Http\Controllers\ChatLogsController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Google OAuth Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/', [PagesController::class, 'main'])->name('main');
Route::get('pricing', [PagesController::class, 'pricing'])->name('pricing');

Route::group(['as' => 'pages.'], static function () {
    Route::get('contact', [PagesController::class, 'contact'])->name('contact');
    Route::get('privacy', [PagesController::class, 'privacy'])->name('privacy');
    Route::get('about', [PagesController::class, 'about'])->name('about');
    Route::get('terms', [PagesController::class, 'terms'])->name('terms');
});

Route::group(['middleware' => ['auth', 'verified']], static function () {
    Route::get('preview', [PagesController::class, 'preview'])->name('preview')->middleware('onboarded');

    Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'dashboard'], static function () {
        Route::get('/', [ChatConfigsController::class, 'index'])->name('dashboard');
        Route::post('chat-config', [ChatConfigsController::class, 'updateChatConfig'])->name('dashboard.update-chat-config');
        Route::post('chat-config/upload-character-image', [ChatConfigsController::class, 'uploadCharacterImage'])->name('dashboard.upload-character-image');

        Route::middleware('onboarded')->group(function () {
            Route::get('settings', [ChatConfigsController::class, 'settings'])->name('settings');
            Route::any('knowledge', [ChatConfigsController::class, 'knowledge'])->name('knowledge');

            Route::get('messages', [ChatLogsController::class, 'index'])->name('messages');
            Route::get('messages/get', [ChatLogsController::class, 'getMessages'])->name('messages.get');
            Route::put('messages/action', [ChatLogsController::class, 'actionMessage'])->name('messages.action')->middleware('paid');
        });
    });
});

Route::group(['middleware' => ['admin'], 'prefix' => 'admin', 'as' => 'admin.'], static function () {
    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
    Route::get('/', [UsersController::class, 'index'])->name('users');

    Route::group(['prefix' => 'dashboard'], function () {
        Route::any('cancelled-subscriptions-logs', [DashboardController::class, 'cancelledSubscriptionsLogs'])->name('dashboard.cancelled-subscriptions-logs');
        Route::any('deleted-accounts-logs', [DashboardController::class, 'deletedAccountsLogs'])->name('dashboard.deleted-accounts-logs');
    });

    /*
     * Users
     */
    Route::group(['prefix' => 'users'], function () {
        Route::any('edit/{id}', [UsersController::class, 'edit']);
        Route::post('delete/{id?}', [UsersController::class, 'delete']);
        Route::post('cancel-subscription-in-database/{id?}', [UsersController::class, 'cancelSubscriptionInDatabase']);
        Route::post('set-subscription', [UsersController::class, 'setSubscription']);
        Route::post('create-business-subscription', [UsersController::class, 'createBusinessSubscription']);

        Route::any('edit/{user}/subscriptions/{subscription}', [UsersController::class, 'subscription']);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['as' => 'forms.'], function () {
    Route::post('contact', [FormsController::class, 'contact'])->name('contact');
});

Route::group(['middleware' => 'auth', 'prefix' => 'account', 'as' => 'account.'], function () {
    Route::get('/', [AccountController::class, 'account'])->name('index');
    Route::get('invoices', [AccountController::class, 'invoices'])->name('invoices');
    Route::get('stats', [AccountController::class, 'stats'])->name('stats');
    Route::post('swap', [AccountController::class, 'swap'])->name('swap');
    Route::post('cancel', [AccountController::class, 'cancel'])->name('cancel');
    Route::post('delete-account', [AccountController::class, 'deleteAccount'])->name('delete-account');
    Route::post('resume', [AccountController::class, 'resume'])->name('resume');
    Route::post('activate', [AccountController::class, 'activate'])->name('activate');
    Route::post('update', [AccountController::class, 'update'])->name('update');

    Route::group(['as' => 'checkout.'], function () {
        Route::get('buy-extra-calls', [AccountController::class, 'buyExtraCalls'])->name('buy-extra-calls');
        Route::get('subscribe', [AccountController::class, 'checkoutSubscribe'])->name('subscribe');
        Route::post('free-subscribe', [AccountController::class, 'freeSubscribe'])->name('subscribe.free');
        Route::post('get-session-id', [AccountController::class, 'getSessionId'])->name('get-session-id');

        Route::post('get-change-card-session-id',
            [AccountController::class, 'getChangeCardSessionId'])->name('get-change-card-session-id');
        Route::get('change-card', [AccountController::class, 'changeCard'])->name('change-card');
    });
});

require __DIR__.'/auth.php';
