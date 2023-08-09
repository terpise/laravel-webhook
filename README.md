# Laravel Webhook

[![Latest Version on Packagist](https://img.shields.io/packagist/v/terpise/laravel-webhook.svg?style=flat-square)](https://packagist.org/packages/terpise/laravel-webhook)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/terpise/laravel-webhook/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/terpise/laravel-webhook/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/terpise/laravel-webhook/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/terpise/laravel-webhook/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/terpise/laravel-webhook.svg?style=flat-square)](https://packagist.org/packages/terpise/laravel-webhook)

A webhook is a way for an app to provide information to another app about a particular event. The way the two apps communicate is with a simple HTTP request.

## Installation

You can install the package via composer:

```bash
composer require terpise/laravel-webhook
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag=webhook-config
```

You can publish the migrations file with:

```bash
php artisan vendor:publish --tag=webhook-migrations
```

Run migrations:
```bash
php artisan migrate
```

You can make client with:

```bash
php artisan webhook:make
```

```bash
php artisan queue:work
```

## Client

Setup callback:
```php
Route::get('callback', [WebhookClientController::class, 'verify'])->middleware(VerifyToken::class);
Route::post('callback', [WebhookClientController::class, 'callback'])->middleware(VerifyToken::class);

// verify use callback authentication when registering
// callback method you can get data from webhook
// middleware for checking the authenticity of the callback, use 'verify_token'

public function verify(Request $request)
{
    return new Response(['challenge' => $request->get('challenge')]);
}

```

Create subscribe:

```bash
$  curl -X POST https://www.domain.com/webhooks/subscribe \
      -F client_id=10 \
      -F client_secret=7b2946535949ae70f015d696d8ac602830ece412 \
      -F callback_url=https://www.domain-client.com/callback \
      -F verify_token=5359435949ae70f015d694656d8ac6
```

View subscribe:

```bash
$  curl -X GET https://www.domain.com/webhooks/subscribe \
      -F client_id=10 \
      -F client_secret=7b2946535949ae70f015d696d8ac602830ece412
```

Unsubscribe:

```bash
$  curl -X POST https://www.domain.com/webhooks/unsubscribe \
      -F client_id=10 \
      -F client_secret=7b2946535949ae70f015d696d8ac602830ece412
```


## Testing

```bash
php artisan webhook:test
```

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Terpise](https://github.com/terpise)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
