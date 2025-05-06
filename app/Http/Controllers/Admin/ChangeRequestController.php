<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangeRequestController extends Controller
{
    public function profileChangeRequests()
    {
        return redirect()->back()->with('errorMessage', 'Under Development');
    }

    public function addressChangeRequests()
    {
        return redirect()->back()->with('errorMessage', 'Under Development');
    }
}
