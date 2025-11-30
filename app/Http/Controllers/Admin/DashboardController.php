<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\ApiLog;
use App\Models\DashboardLog;
use App\Models\News;
use App\Models\NewsChangeLog;
use App\Models\Setting;
use App\Models\Ticker;
use App\Models\User;
use App\Services\NewsScraper\DataResolvers\SentimentResolver;
use App\Services\NewsScraper\DataResolvers\TagsResolver;
use App\Services\NewsScraper\DataResolvers\TickersResolver;
use App\Services\NewsScraper\NewsScraper;
use App\Services\NewsScraper\Scrapers\Contracts\ScraperContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use const PHP_EOL;

class DashboardController extends Controller
{
    public function cancelledSubscriptionsLogs(Request $request)
    {
        $path = storage_path('app/public/cancelled_subscriptions_log.log');

        if ($request->isMethod('post')) {
            $result = file_exists($path) ? unlink($path) : null;

            return response()->json(['result' => $result]);
        }

        $log = file_exists($path) ? file_get_contents($path) : null;

        return view('admin.dashboard.cancelled-subscriptions-logs', compact('log'));
    }

    public function deletedAccountsLogs(Request $request)
    {
        $path = storage_path('app/public/deleted_account_log.log');

        if ($request->isMethod('post')) {
            $result = file_exists($path) ? unlink($path) : null;

            return response()->json(['result' => $result]);
        }

        $log = file_exists($path) ? file_get_contents($path) : null;

        return view('admin.dashboard.deleted-accounts-logs', compact('log'));
    }
}
