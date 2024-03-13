<?php
declare(strict_types=1);

return [
    "enabled" => true,
    "server_url" => env("PAYKEEPER_SERVER", "https://server.paykeeper.ru"),
    "post_responses_url" => env("PAYKEEPER_POST_URL", "https://localhost/narfu/payments/callback"),
    "post_secret" => env("PAYKEEPER_POST_SECRET", "password"),
    "login" => env("NARFU_PAYMENT_LOGIN", "admin"),
    "password" => env("NARFU_PAYMENT_PASSWORD", "admin"),
    "yookassa_api_url" => env("YOOKASSA_API_URL", "https://api.yookassa.ru/v3"),
    "is_test" => env("NARFU_PAYMENT_TEST_MODE", true),
];
