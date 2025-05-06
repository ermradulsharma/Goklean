<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayRepository
{
    /**
     * Create an order on Razorpay
     *
     * @param $paymentId
     * @param $amount
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function createOrder($paymentId, $amount)
    {
        return Http::withBasicAuth(config('services.razorpay.key'), config('services.razorpay.secret'))
            ->withHeaders(['Content-Type' => 'application/json']
            )->post('https://api.razorpay.com/v1/orders', [
                'receipt' => $paymentId,
                'amount' => $amount,
                'payment_capture' => 1,
                'currency' => 'INR'
            ]);
    }

    /**
     * Verify Razorpay payment with signature
     *
     * @param $attributes
     * @return bool
     */
    public function verifyPayment($attributes)
    {
        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $api->utility->verifyPaymentSignature($attributes);
            $success = true;
        } catch (SignatureVerificationError $e) {
            $success = false;
            Log::channel('daily')->error($e->getMessage());
        }
        return $success;
    }

    /**
     * Verify Razorpay webhook signature
     *
     * @param $webhookBody
     * @param $webhookSignature
     * @return bool
     */
    public function verifyWebhook($webhookBody, $webhookSignature)
    {
        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $api->utility->verifyWebhookSignature($webhookBody, $webhookSignature, config('services.razorpay.webhook_secret'));
            $success = true;
        } catch (SignatureVerificationError $e) {
            $success = false;
            Log::channel('daily')->error($e->getMessage());
        }
        return $success;
    }
}
