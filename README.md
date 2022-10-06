<p align="center"><img src="https://github.com/icodestuff-io/laravel-mailwind/blob/main/mailwind-logo-transparent.png?raw=true" alt="Laravel Enum" width="250" style="margin-bottom: 20px"></p>
<p align="center">
<a href="https://packagist.org/packages/icodestuff/mailwind"><img src="https://img.shields.io/packagist/v/icodestuff/laravel-mailwind.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href="https://packagist.org/packages/bensampo/laravel-enum"><img src="https://img.shields.io/github/workflow/status/icodestuff-io/laravel-mailwind/run-tests?label=tests" alt="GitHub Tests Action Status"></a>
<a href="https://github.com/icodestuff-io/laravel-mailwind/actions?query=workflow%3A'Fix+PHP+code+style+issues'+branch%3Amain'"><img src="https://img.shields.io/github/workflow/status/icodestuff-io/laravel-mailwind/Fix%20PHP%20code%20style%20issues?label=code%20style" alt="GitHub Code Style Action Status"></a>
<a href="https://packagist.org/packages/icodestuff/mailwind"><img src="https://img.shields.io/packagist/dt/icodestuff/mailwind..svg?style=flat-square" alt="Total Downloads"></a>
</p>

![mailwind-example](./mailwind-screenshot.png)

## About Laravel MailWind
Use TailwindCSS to design your Laravel Mailables instead of relying on markdown or inline styles.


## Installation

You can install the package via composer:

```bash
composer require icodestuff/laravel-mailwind
```

You can publish and run the migrations with:


You can publish the config file with:

```bash
php artisan vendor:publish --tag="maiwlind-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag=":package_slug-views"
```

## Usage

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Solomon Antoine](https://github.com/solomon04)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## TODO
- [ ] Write test cases
- [ ] Test in Real Laravel application
- [ ] Document setup process
