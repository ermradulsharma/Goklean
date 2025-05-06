<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Http;

class SmsHelper
{
    /**
     * Send SMS using MSG91 OneAPI
     *
     * @param $body
     * @return bool
     */
    public function sendSMS($body)
    {
        $response = Http::withHeaders([
                'authkey' => config('services.msg91.key'),
                'Content-Type' => 'application/json']
        )->post(config('services.msg91.endpoint'), $body);

        if($response->successful()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get MSG91 Flow ID of an SMS
     * key => flow_id
     *
     * @param $key
     * @return string|null
     */
    public function getFlowId($key)
    {
        $templates = [
            'wash_completed' => '6229f8679a4556339a079ff4',
            'verification_code' => '62387fa07f68da026d6eac05',
            'otp' => '6238800210a7e27cb5356d24'
        ];

        return isset($templates[$key]) ? $templates[$key] : null;
    }
}
