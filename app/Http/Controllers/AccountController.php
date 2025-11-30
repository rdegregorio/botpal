<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeCardRequest;
use App\Http\Requests\SwapSubscriptionRequest;
use App\Models\ChatLog;
use App\Models\Subscription;
use App\Models\User;
use App\Services\OpenAIService;
use App\Services\Subscriptions\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{

    public function invoices(Request $request)
    {
        $items = $request->user()->invoices()->map(static function ($item) {
            return [
                'url' => $item->hosted_invoice_url,
                'date' => $item->date()->toFormattedDateString(),
                'total' => $item->total(),
            ];
        });

        return response()->json($items);
    }

    public function changeCard(ChangeCardRequest $request)
    {
        try {
            SubscriptionService::changeCard($request->user(), $request->input('session_id'));
        } catch (\Exception $exception) {
            return redirect()->route('dashboard')->with('danger', $exception->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Card was changed');
    }

    public function getSessionId(Request $request)
    {
        $this->validate($request, [
            'plan' => [
                'required',
                'string',
                Rule::in(Subscription::getAutoPayablePlans()),
            ],
        ]);

        try {
            $session = SubscriptionService::createSession(Auth::user(), $request->input('plan'));
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 403);
        }

        return response()->json(['sessionId' => $session->id]);
    }

    public function getChangeCardSessionId(Request $request)
    {
        try {
            $session = SubscriptionService::createSession(Auth::user(), null, true);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 403);
        }

        return response()->json(['sessionId' => $session->id]);
    }

    public function checkoutSubscribe(Request $request)
    {
        $this->validate($request, [
            'session_id' => 'required|string',
        ]);

        /** @var User $user */
        $user = Auth::user();

        try {
            SubscriptionService::subscribeByCheckout($user, $request->input('session_id'));
        } catch (\Exception $exception) {
            return redirect()->route('dashboard')->with('danger', $exception->getMessage());
        }

        /*
         * If it's the first user subscription we presume
         * that the user just registered and send a notification
         */
//        if ($user->subscriptions()->count() === 1) {
//            $user->notify(new UserRegisteredNotification());
//        }

        $url = route('account.index');
        $message = 'Subscribed successfully.';

        return redirect($url)->with('success', $message);
    }

    public function freeSubscribe(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        try {
            SubscriptionService::createFreeSubscription($user);
        } catch (\Exception $exception) {
            return redirect()->route('dashboard')->with('danger', $exception->getMessage());
        }

        $url = route('account.index');
        $message = 'Subscribed successfully.';

        return redirect($url)->with('success', $message);
    }

    public function buyExtraCalls(Request $request)
    {
        $this->validate($request, [
            'session_id' => 'required|string',
        ]);

        /** @var User $user */
        $user = Auth::user();

        try {
            SubscriptionService::buyExtraCalls($user, $request->input('session_id'));
        } catch (\Exception $exception) {
            return redirect()->route('dashboard')->with('danger', $exception->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Extra Calls added successfully');
    }

    public function swap(SwapSubscriptionRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Subscription $subscription */
        $subscription = $user->getCurrentActiveSubscription();
        $type = (int)$request->input('planType');

        if($subscription?->isFree()) {
            $subscription->delete();
            return redirect()->route('pricing')
                ->with('warning', 'Please subscribe to Basic or Premium - Start your trial');
        }

        $typePrice = Subscription::getPriceByType($type);
        $upgradeOrDowngradeText = $subscription->price < $typePrice ? 'Upgrade' : 'Downgrade';

        $msg = now()->toDateTimeString();
        $msg .= " $user->email";
        $msg .= " | $upgradeOrDowngradeText Subscription.\r\n";

        try {
            SubscriptionService::swap($user, $type);
            $this->addToCancelSubscriptionLogs($msg);
        } catch (\Exception $exception) {

            $msg = "[Error] $msg";
            $this->addToCancelSubscriptionLogs($msg);

            return redirect()->back()->with('danger', $exception->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Changed successfully');
    }

    public function cancel(Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string|max:5000',
        ]);

        $user = Auth::user();

        try {
            SubscriptionService::cancel($user);

            $s = $user->getCurrentActiveSubscription();
            $msg = now()->toDateTimeString();
            $msg .= " $user->email";
            $msg .= " | Plan: {$s->getName()} | Reason: ";
            $msg .= strip_tags($request->input('reason')) . "\r\n";

            $this->addToCancelSubscriptionLogs($msg);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }

        return response()->json(['result' => true]);
    }

    public function deleteAccount(Request $request)
    {
        $this->authorize('delete-account');

        abort_unless($request->json(), 403, 'Make sure you send the request from the modal form.');

        $user = Auth::user();

        Auth::logout();

        try {
            $msg = now()->toDateTimeString();
            $msg .= " $user->email" . "\r\n";

            $this->addToDeletedAccountLogs($msg);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }

        $user->delete();

        return response()->json(['result' => true]);
    }

    public function resume()
    {
        $user = Auth::user();

        try {
            SubscriptionService::resume($user);

            $msg = now()->toDateTimeString();
            $msg .= " $user->email";
            $msg .= " | Resumed Subscription.\r\n";

            $this->addToCancelSubscriptionLogs($msg);
        } catch (\Exception $exception) {
            return redirect()->route('dashboard')->with('danger', $exception->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Resumed successfully');
    }

    public function activate()
    {
        $user = Auth::user();

        try {
            SubscriptionService::activate($user);
        } catch (\Exception $exception) {
            return redirect()->route('dashboard')->with('danger', $exception->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Activated successfully');
    }

    public function update(Request $request)
    {
        $field = $request->input('field');

        // Different max lengths for different fields
        $maxLength = $field === 'open_ai_token' ? 500 : 255;

        $this->validate($request, [
            'field' => 'required|in:name,email,password,open_ai_token,open_ai_model',
            'value' => "required|string|min:3|max:{$maxLength}",
        ]);

        $field = $request->input('field');
        $value = $request->input('value');

        if ($field === 'password') {
            $value = bcrypt($value);
        }

        if ($field === 'open_ai_token') {
            validator(['open_ai_token' => $value], [
                'open_ai_token' => [
                    static function ($attribute, $value, $fail) {
                        if (!OpenAIService::validateToken($value)) {
                            $fail('Invalid OpenAI token');
                        }
                    },
                ],
            ])->validate();
        }

        if ($field === 'open_ai_model') {
            validator(['open_ai_model' => $value], [
                'open_ai_model' => ['required', Rule::in(OpenAIService::AVAILABLE_MODELS)],
            ])->validate();
        }

        if ($field === 'email') {
            validator(['email' => $value], [
                'email' => 'required|email|unique:users,email,'.Auth::id(),
            ])->validate();
        }

        return response()->json(Auth::user()->update([$field => $value]));
    }

    private function addToCancelSubscriptionLogs(string $msg): void
    {
        file_put_contents(storage_path('app/public/cancelled_subscriptions_log.log'), $msg, FILE_APPEND);
    }

    private function addToDeletedAccountLogs(string $msg): void
    {
        file_put_contents(storage_path('app/public/deleted_account_log.log'), $msg, FILE_APPEND);
    }

    public function account(Request $request)
    {
        $subscription = Auth::user()->getCurrentActiveSubscription();

        if(!$subscription) {
            return redirect()->route('pricing');
        }

        if (SubscriptionService::onTrialAndLimitsReached($subscription)) {
            $msg = "You have used your free {$subscription->getAvailableTrialRequests()} trial calls. Please activate your subscription today.";

            $request->session()->flash('warning', $msg);
        }

        return view('account.index', compact('subscription'));
    }

    public function billing(Request $request)
    {
        $subscription = Auth::user()->getCurrentActiveSubscription();

        if(!$subscription) {
            return redirect()->route('pricing');
        }

        return view('account.billing', compact('subscription'));
    }

    public function usage(Request $request)
    {
        return view('account.usage');
    }

    public function stats(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date|date_format:Y-m-d',
        ]);

        if ($request->input('start_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'));
            $endDate = $startDate->clone()->addDays(30);
        } else {
            $endDate = Carbon::today()->endOfDay();
            $startDate = Carbon::today()->subDays(29);
        }

        $messages = ChatLog::whereRelation('chatConfig', 'user_id', Auth::id())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            ]);

        $dates = [];
        $data = [];

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('M. jS');

            $foundMessage = $messages->firstWhere('date', $date->format('Y-m-d'));
            $data[] = $foundMessage->count ?? 0;
        }

        return response()->json([
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'AI Bot Responses',
                    'data' => $data,
                    'backgroundColor' => 'rgba(32, 178, 170, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1
                ]
            ]
        ]);
    }
}
