<?php

return [
    'whatsapp' => [
        'number' => env('JETFLY_WHATSAPP_NUMBER'),
        'message' => env('JETFLY_WHATSAPP_MESSAGE', 'Hi, I need help with a Jet Fly booking.'),
    ],

    'admin_notify_email' => env('JETFLY_ADMIN_NOTIFY_EMAIL', env('MAIL_FROM_ADDRESS', 'hello@example.com')),

    'sms' => [
        'driver' => env('JETFLY_SMS_DRIVER', 'log'),
        'twilio_sid' => env('TWILIO_ACCOUNT_SID'),
        'twilio_token' => env('TWILIO_AUTH_TOKEN'),
        'twilio_from' => env('TWILIO_FROM_NUMBER'),
    ],
];
