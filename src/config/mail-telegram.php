<?php

return [
    /*
     |----------------------------------------------------------------------
     | Telegram Mail Configuration
     |----------------------------------------------------------------------
     | You can set default values here or rely on .env for environment-based
     | configuration. For instance:
     |
     | 'chat_id' => env('TELEGRAM_CHAT_ID', '123456789'),
     | 'bot_token' => env('TELEGRAM_BOT_TOKEN', 'BOT_TOKEN'),
     |
    */

    'chat_id' => env('MAIL_USERNAME', ''),
    'bot_token' => env('MAIL_PASSWORD', ''),
];