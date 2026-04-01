<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

class LogSmsSender implements SmsSender
{
    public function send(string $phone, string $message): void
    {
        Log::channel('single')->info('[SMS stub] '.$phone.' — '.$message);
    }
}
