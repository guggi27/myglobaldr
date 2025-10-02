<?php

return [
    "app_name" => "My Global Dr",
    "session_timezone_key" => "my_global_dr_session_timezone",
    "currency" => "PKR",
    "stripe_publishable_key" => env("stripe_publishable_key"),
    "stripe_secret_key" => env("stripe_secret_key"),
    "twilio_sid" => env("twilio_sid"),
    "twilio_secret" => env("twilio_secret"),
    "twilio_api_key" => env("twilio_api_key"),
    "twilio_auth_token" => env("twilio_auth_token"),
    "twilio_phone" => env("twilio_phone"),
];