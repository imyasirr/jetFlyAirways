<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwilioSmsSender implements SmsSender
{
    public function send(string $phone, string $message): void
    {
        $sid = config('jetfly.sms.twilio_sid');
        $token = config('jetfly.sms.twilio_token');
        $from = config('jetfly.sms.twilio_from');

        if (! is_string($sid) || $sid === '' || ! is_string($token) || $token === '' || ! is_string($from) || $from === '') {
            Log::warning('[SMS] Twilio not configured; message not sent.', ['phone' => $phone]);

            return;
        }

        $url = 'https://api.twilio.com/2010-04-01/Accounts/'.$sid.'/Messages.json';

        $response = Http::withBasicAuth($sid, $token)
            ->asForm()
            ->post($url, [
                'From' => $from,
                'To' => $phone,
                'Body' => $message,
            ]);

        if (! $response->successful()) {
            Log::error('[SMS] Twilio error', ['status' => $response->status(), 'body' => $response->body()]);
        }
    }
}
