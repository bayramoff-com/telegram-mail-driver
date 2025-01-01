<?php

namespace BayramoffCom\TelegramMailDriver\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use BayramoffCom\TelegramMailDriver\Transport\TelegramTransport;

class TelegramMailServiceProvider extends ServiceProvider
{
    public function register() {}


    public function boot()
    {
        // Publish the config file
        $this->publishes([
            __DIR__ . '/../config/mail-telegram.php' => config_path('mail-telegram.php'),
        ], 'telegram-mail-config');

        // Extend the mail manager to add a 'telegram' driver.
        // Extend the mail manager
        $this->app->resolving('mail.manager', function (MailManager $mailManager) {
            // Create a "telegram" driver
            $mailManager->extend('telegram', function () {
                // Instead of $config['bot_token'] etc., we'll use $config['username'] and $config['password']
                // because we are intentionally using MAIL_USERNAME for the chat_id, and MAIL_PASSWORD for bot_token.

                $chatId = config('mail-telegram.chat_id');
                $botToken = config('mail-telegram.bot_token');

                return new TelegramTransport($chatId, $botToken);
            });
        });
    }
}
