<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Http;

class FcmHelper
{
    /**
     * Send Push Notification using FCM
     *
     * @param $body
     * @return bool
     */
    public function sendPushNotification($body)
    {
        $response = Http::withHeaders([
                'Authorization' => 'key='.config('services.fcm.key'),
                'Content-Type' => 'application/json']
        )->post(config('services.fcm.endpoint'), $body);

        if($response->successful()) {
            return true;
        } else {
            return false;
        }
    }
}
