<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function main()
    {
        return view('pages.main');
    }

    public function pricing() {
        if(\Auth::user()?->getCurrentActiveSubscription()) {
            return redirect()->route('account.index');
        }
        return view('pages.pricing');
    }

    public function contact() {
        return view('pages.contact');
    }
    public function privacy() {
        return view('pages.privacy');
    }
    public function about() {
        return view('pages.about');
    }
    public function terms() {
        return view('pages.terms');
    }

    public function preview()
    {
        if(!\Auth::user()?->chatConfigLatest()->exists()) {
            return redirect()->route('settings')->with('warning', 'Please update your chat config first to use preview.');
        }

        return view('pages.preview');
    }
}
