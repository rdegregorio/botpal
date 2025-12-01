<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ChatConfig;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\Subscriptions\SubscriptionService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tos' => ['required', 'accepted'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        // Create default chat config so user can access settings/knowledge
        ChatConfig::create([
            'user_id' => $user->id,
            'name' => 'My Chatbot',
            'general_prompt' => 'You are a helpful AI assistant.',
            'welcome_message' => "Hi there! I'm an AI support agent. How can I help?",
            'character' => 3,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to plan selection page
        return redirect()->route('select-plan');
    }

    /**
     * Display the plan selection view.
     */
    public function selectPlan(): View|RedirectResponse
    {
        $user = Auth::user();

        // If user already has a subscription, redirect to dashboard
        if ($user->getCurrentActiveSubscription()) {
            return redirect('/dashboard');
        }

        return view('auth.select-plan');
    }

    /**
     * Handle plan selection.
     */
    public function storePlan(Request $request): RedirectResponse
    {
        $request->validate([
            'plan' => ['required', 'string', 'in:free,premium,enterprise'],
        ]);

        $user = Auth::user();

        // For now, only free plan is available
        if ($request->plan === 'free') {
            SubscriptionService::createFreeSubscription($user);
        }

        return redirect()->route('knowledge')->with('success', 'Welcome! Let\'s set up your chatbot knowledge base.');
    }
}
