
# Telegram Mail Driver for Laravel

A custom mail driver for Laravel (9, 10, 11, and beyond) that sends messages via the **Telegram Bot API** instead of traditional email. Perfect for sending real-time notifications or alerts directly to Telegram chats.

## Features

- **Plug-and-Play**: Integrates seamlessly into Laravel's Mail system.
- **Customizable**: Configure your Telegram chat ID and bot token via `.env`.
- **Lightweight**: Uses Guzzle for HTTP requests and Symfony Mailer for compatibility.

---

## Installation

### 1. Add the Package

Run:

```bash
composer require bayramoff-com/telegram-mail-driver:0.1.4
```

### 2. Publish the Config

After installation, publish the configuration file:

```bash
php artisan vendor:publish --provider="BayramoffCom\TelegramMailDriver\Providers\TelegramMailServiceProvider" --tag=telegram-mail-config
```

This creates a `config/telegram-mail.php` file in your Laravel app.

### 3. Update `.env`

Set your Telegram bot details in `.env`:

```dotenv
MAIL_MAILER=telegram
MAIL_USERNAME=123456789         # Telegram chat_id
MAIL_PASSWORD=999999:ABC-XYZ123 # Telegram bot token
```

### 4. Update `config/mail.php`

Add the `telegram` mailer to your `config/mail.php`:

```php
return [
    'mailers' => [
        // Existing mailers...
        'telegram' => [
            'transport' => 'telegram',
        ],
    ],
];
```

Clear your config cache if needed:

```bash
php artisan config:clear
php artisan cache:clear
```

---

## Usage

Use Laravel's `Mail` facade as usual:

```php
use Illuminate\Support\Facades\Mail;

Route::get('/test-telegram', function () {
    Mail::raw('Hello from Telegram Mail Driver!', function ($message) {
        $message->subject('Test Telegram Mail Driver');
        $message->to('ignored@example.com'); // Ignored by TelegramTransport
    });

    return 'Message sent to Telegram!';
});
```

Messages will be sent to the Telegram `chat_id` defined in `.env`.
## License

This package is open-sourced software licensed under the [MIT License](LICENSE).

Enjoy sending Laravel emails via Telegram!