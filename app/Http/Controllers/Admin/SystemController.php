<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    /**
     * Clear application caches.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCache()
    {
        $commands = [
            'storage:link',
            'config:cache',
            'config:clear',
            'cache:clear',
            'route:clear',
            'view:clear',
            'auth:clear-resets',
            'event:clear',
            'queue:clear',
            'queue:flush',
            'schedule:clear-cache'
        ];

        $output = [];
        $errors = [];

        foreach ($commands as $command) {
            try {
                Artisan::call($command);
                $output[$command] = Artisan::output();
            } catch (\Exception $e) {
                $errors[$command] = $e->getMessage();
            }
        }

        return $errors
            ? response()->json(['errors' => $errors], 500)
            : response()->json(['message' => 'All cache cleared successfully!']);
    }
}
