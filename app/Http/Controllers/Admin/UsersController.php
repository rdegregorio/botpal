<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Subscriptions\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use const SORT_REGULAR;

class UsersController extends Controller
{

    public function index(Request $request)
    {

        $sort = $request->input('sort', 'id');
        $order = (int)(bool)$request->input('order');

        $direction = $order ? 'desc' : 'asc';

        $users = User::with(['subscriptions', 'chatConfigLatest:id,user_id,type']);

        if ((int)$request->input('not-verified') === 1) {
            $users->whereNull('email_verified_at');
        } else {
            $users->whereNotNull('email_verified_at');
        }

        if (in_array($sort, ['id', 'name', 'email', 'created_at'])) {

            $users->orderBy($sort, $direction);
            $users = $users->paginate(50);

        } else {

            $users = $users->get();

            if ($sort === 'api_daily_usage_count') {
                $users = $users->sortBy("$sort", SORT_REGULAR, $order);
            } else {
                $users = $users->map(static function ($user) {
                    /** @var User $user */
                    $user->active_subscription = $user->getCurrentActiveSubscription();
                    return $user;
                })->sortBy("active_subscription.$sort", SORT_REGULAR, $order);
            }

            $users = new LengthAwarePaginator($users, $users->count(), 100, $request->input('page', 1));

        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.users._item-rows', compact('users', 'order', 'sort'))->render(),
                'lastPage' => $users->lastPage(),
            ]);
        }

        return view('admin.users.index', compact('users', 'order', 'sort'));
    }

    public function delete($id)
    {
        abort_if(Auth::id() === (int)$id, 403);

        $user = User::findOrFail($id);
        $hasSubscriptions = $user->subscriptions()->exists();

        $deleted = $user->delete();

        return response()->json(['result' => $deleted]);
    }


    public function edit(Request $request, $id)
    {
        /** @var User $user */
        $user = User::findOrFail($id);

        /** @var Subscription $s */
        $s = $user->getCurrentActiveSubscription();

        if ($request->isMethod('get')) {
            return view('admin.users.create-edit', compact('user', 's'));
        }

        $data = $this->validate($request, [
            'cus_id' => 'nullable|string|starts_with:cus_',
            'sub_id' => 'nullable|string|starts_with:sub_',
            'custom_price' => 'sometimes|integer|min:0',
            'custom_available_trial_requests' => 'nullable|integer|min:0',
            'expires_at' => 'required|date_format:Y-m-d H:i:s',
            'trial_ends_at' => 'required|date_format:Y-m-d H:i:s',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);

        $user->email = $data['email'];
        $user->stripe_id = $data['cus_id'];
        $user->trial_ends_at = empty($data['trial_ends_at']) ? null : $data['trial_ends_at'];
        $user->save();

        $s->stripe_id = $data['sub_id'] ?? '';
        $s->expires_at = $data['expires_at'];

        if ($s->isBusiness()) {
            $s->custom_price = $data['custom_price'];
        }

        $s->save();

        return redirect()->action([self::class, 'index'])->with('success', 'Data saved');
    }

    public function subscription(Request $request, User $user, Subscription $subscription)
    {
        if ($request->isMethod('get')) {
            return view('admin.users.subscription', compact('user', 'subscription'));
        }

        $data = $this->validate($request, [
            'type' => ['required', Rule::in(array_keys(Subscription::PLAN_NAMES))],
            'stripe_id' => 'nullable',
            'stripe_plan' => 'nullable',
            'trial_requests_count' => 'nullable|int',
            'requests_count' => 'nullable|int',
            'custom_available_requests' => 'nullable|int',
            'custom_price' => 'nullable',
            'ends_at' => 'nullable|date_format:Y-m-d H:i:s',
            'trial_ends_at' => 'nullable|date_format:Y-m-d H:i:s',
            'expires_at' => 'nullable|date_format:Y-m-d H:i:s',
            'created_at' => 'nullable|date_format:Y-m-d H:i:s',
            'updated_at' => 'nullable|date_format:Y-m-d H:i:s',
            'canceled_at' => 'nullable|date_format:Y-m-d H:i:s',
            'custom_available_trial_requests' => 'nullable|int',
            'extra_calls_total' => 'nullable|int',
            'extra_calls_sent' => 'nullable|int',
        ]);

        $subscription->forceFill($data);
        $subscription->save();

        return redirect()->action([self::class, 'subscription'], [$user->id, $subscription->id])->with('success', 'Data saved');
    }

    public function cancelSubscriptionInDatabase(Request $request, $id)
    {
        abort_if(Auth::id() === (int)$id, 403);

        $user = User::findOrFail($id);
        $s = $user->getCurrentActiveSubscription();

        return response()->json(['result' => $s->cancelInDataBase()]);
    }

    public function setSubscription(Request $request)
    {
        $data = $this->validate($request, [
            'user_id' => 'bail|required|exists:users,id',
            'type' => [
                'required',
                'integer',
                Rule::in([Subscription::PLAN_BUSINESS]),
            ],
            'limit' => 'nullable|integer',
            'price' => 'nullable|integer',
        ]);

        $user = User::findOrFail($data['user_id']);

        if ((int)$data['type'] === Subscription::PLAN_BUSINESS) {
            SubscriptionService::createBusinessSubscription($user, $data['limit'], $data['price']);
        }

        return response()->json(['success' => true]);
    }

    public function createBusinessSubscription(Request $request)
    {
        $data = $this->validate($request, [
            'email' => 'required|email',
            'name' => 'nullable|string',
            'password' => 'nullable|string',
            'stripe_id' => 'nullable|string',
            'limit' => 'required|integer',
            'price' => 'required|integer',
        ]);

        /** @var User $user */
        $user = User::whereEmail($data['email'])->firstOrNew(['email' => $data['email']]);

        $messages = [];

        if ($user->exists) {

            $messages[] = 'Existing user has been updated.';

            $s = $user->getCurrentActiveSubscription();

            if ($s) {
                $messages[] = 'The user already has a subscription. Please check on stripe if the user doesn\'t have any unwanted active subscriptions.';
            }
        } else {
            $password = empty($data['password']) ? \Str::random(5) : $data['password'];
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->stripe_id = $data['stripe_id'];
            $user->password = bcrypt($password);
            $user->save();

            $messages[] = 'User has been created with email: ' . $data['email'] . ', password: ' . $password;
        }

        SubscriptionService::createBusinessSubscription($user, $data['limit'], $data['price']);

        $messages[] = 'Plan details: Limit is ' . $data['limit'] . ', Price is ' . $data['price'];

        return redirect()->action([self::class, 'index'])->with('success', $messages);
    }
}
