<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function general()
    {
        return redirect()->back()->with('errorMessage', 'Settings Coming Soon');
    }

    public function notification()
    {
        return redirect()->back()->with('errorMessage', 'Settings Coming Soon');
    }

    public function payment()
    {
        return redirect()->back()->with('errorMessage', 'Settings Coming Soon');
    }
}
