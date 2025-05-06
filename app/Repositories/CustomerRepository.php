<?php

namespace App\Repositories;

use App\Helpers\SmsHelper;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class CustomerRepository
{
    /**
     * Send otp
     *
     * @param Customer $customer
     * @param $length
     * @return bool
     */
    public function sendOtp(Customer $customer, $length)
    {
        $otp = generateNumericOTP($length);

        $customer->otp = $otp;
        $customer->otp_expire_at = Carbon::now()->addMinutes(10);
        $customer->update();

        $sms = new SmsHelper();
        return $sms->sendSMS([
            'flow_id' => $sms->getFlowId('otp'),
            'mobiles' => '91' . $customer->mobile,
            'code' => $otp
        ]);
    }

    /**
     * Send mobile verification code
     *
     * @param Customer $customer
     * @param $length
     * @return bool
     */
    public function sendVerificationCode(Customer $customer, $length)
    {
        $otp = generateNumericOTP($length);

        $customer->otp = $otp;
        $customer->otp_expire_at = Carbon::now()->addMinutes(10);
        $customer->update();

        $sms = new SmsHelper();
        return $sms->sendSMS([
            'flow_id' => $sms->getFlowId('verification_code'),
            'mobiles' => '91' . $customer->mobile,
            'code' => $otp
        ]);
    }

    public function verifyCode(Customer $customer, $code)
    {
        $otpVerified = false;

        if ($code == $customer->otp && $customer->otp_expire_at > Carbon::now()) {
            $otpVerified = true;
            $customer->mobile_verified_at = Carbon::now();
            $customer->save();
        }
        return $otpVerified;
    }
}
