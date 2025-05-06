<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppController extends Controller
{
    /**
     * Api version
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(["api_name" => "GoKleen API", "api_version" => "1.0"], 200);
    }

    /**
     * App settings
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function settings()
    {
        return response()->json(["app_name" => "GoKleen"], 200);
    }
}
