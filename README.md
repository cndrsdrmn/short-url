# **Short URL Package**

[![Packagist Version](https://img.shields.io/packagist/v/cndrsdrmn/short-url?label=stable)](https://packagist.org/packages/cndrsdrmn/short-url)
[![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/cndrsdrmn/short-url/tests.yml?logo=github&label=CI)](https://github.com/cndrsdrmn/short-url/actions)
[![GitHub License](https://img.shields.io/github/license/cndrsdrmn/short-url)](https://github.com/cndrsdrmn/short-url/blob/master/LICENSE)

A lightweight and flexible Laravel package for generating and managing short URLs. This package simplifies the process of creating short links with customizable options, middleware, and bot protection.

---

## **Features**

- 📏 **Customizable token formats and lengths**: Choose from mixed, numeric, or alphabetic tokens.
- 🛡 **Bot protection**: Optionally block requests from known bots.
- ⚡ **Quick setup**: Easily integrate into existing Laravel projects.
- 🔒 **Token encryption**: Secure your tokens with a custom encryption callback.
- 🎛 **Middleware support**: Add custom middleware to short URL routes.

---

## **Installation**

Install the package via Composer:

```bash
composer require cndrsdrmn/short-url
```

---

## **Configuration**

Publish the configuration file:

```bash
php artisan vendor:publish --tag=short-url-config
```

The configuration file `config/short-url.php` will be published. It includes the following options:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | URL Prefix
    |--------------------------------------------------------------------------
    |
    | This value sets the prefix for the short URL routes. It will be used as the
    | base path for accessing the short URLs.
    |
    */
    'prefix' => '/s',

    /*
    |--------------------------------------------------------------------------
    | Allowed Schemas
    |--------------------------------------------------------------------------
    |
    | Specify the schema can be allowed for generated token.
    |
    */
    'allowed_schemas' => [
        'http',
        'https'
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Connection
    |--------------------------------------------------------------------------
    |
    | Specify the database connection to be used for storing short URLs. If null,
    | the default connection defined in the database configuration will be used.
    |
    */
    'connection' => null,

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Define the middleware to apply to the short URL routes. By default, the routes
    | are throttled to prevent abuse. You can modify or extend the middleware stack
    | as needed.
    |
    */
    'middleware' => [
        'throttle:100,1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Token Format
    |--------------------------------------------------------------------------
    |
    | Specify the format used to generate tokens for short URLs. Supported values:
    | - "bothify": Mix of letters and numbers (e.g., `A1b2C`)
    | - "numerify": Numbers only (e.g., `12345`)
    | - "lexify": Letters only (e.g., `ABCDE`)
    |
    */
    'token_format' => 'bothify',

    /*
    |--------------------------------------------------------------------------
    | Token Length
    |--------------------------------------------------------------------------
    |
    | Define the length of the token generated for the short URLs. The token's
    | length determines how many characters will be included in the generated URL.
    |
    */
    'token_length' => 5,

    /*
    |--------------------------------------------------------------------------
    | Block Bots
    |--------------------------------------------------------------------------
    |
    | If enabled, requests from known bots will be blocked from accessing the
    | short URLs. This is useful for preventing automated access and ensuring
    | accurate analytics.
    |
    */
    'should_block_bots' => true,
];
```

---

## **Usage**

### **Basic Usage**

#### Shorten a URL
You can easily generate a short URL with the `ShortUrl` facade:

```php
use Cndrsdrmn\ShortUrl\Facades\ShortUrl;

$shortUrl = ShortUrl::shorten('https://example.com');
echo $shortUrl; // Outputs the short URL
```

---

### **Advanced Usage**

#### Customizing the Builder
Customize your short URL with the `make` method:

```php
use Cndrsdrmn\ShortUrl\Facades\ShortUrl;

$builder = ShortUrl::make('https://example.com', [
    'is_single_use' => true,
]);

$shortUrl = $builder->create();
echo $shortUrl->accessLink;
```

#### Setting Custom Headers and Queries
```php
$builder = ShortUrl::make('https://example.com')
    ->headers(['X-Custom-Header' => 'value'])
    ->queries(['utm_source' => 'newsletter']);

$shortUrl = $builder->create();
echo $shortUrl->accessLink;
```

---

## **Routes**

By default, the package automatically registers a route for handling short URL redirections. If you want to customize the routing:

1. Disable route registration on boot in `App\Providers\AppServiceProvider`:
   ```php
   ShortUrl::ignoreRoutes();
   ```
2. Define your own route in `routes/web.php`:
   ```php
   use App\Http\Controllers\RedirectController;

   Route::get('/s/{token}', [RedirectController::class, 'handle']);
   ```

---

## **Security**

### **Encrypt Tokens**
Set a custom encryption callback to secure your tokens:

```php
ShortUrl::encryptTokenUsing(function ($token) {
    return encrypt($token);
});
```

---

## **Testing**

Run the tests with PHPUnit:

```bash
php artisan test
```

---

## **License**

This package is open-sourced software licensed under the [MIT license](./LICENSE).

---

## **Contributing**

Contributions are welcome! Please see more detail on [CONTRIBUTING.md](./CONTRIBUTING.md).

---

## **Credits**

- **Author:** [Candra Sudirman](https://github.com/cndrsdrmn)
- **Inspired by:** URL shortening services like Bitly.

