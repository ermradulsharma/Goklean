<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Inertia\Inertia;

class SettingController extends Controller
{
    public function general()
    {
        return Inertia::render('Admin/Settings/General');
    }

    public function notification()
    {
        return Inertia::render('Admin/Settings/Notification');
    }

    public function payment()
    {
        return Inertia::render('Admin/Settings/Payment');
    }
}
