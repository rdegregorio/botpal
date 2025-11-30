<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function users()
    {
        return view('admin.users.index');
    }
}
