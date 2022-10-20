<picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://www.codelabx.ltd/assets/images/xtend-laravel/xtend-laravel-banner-dark.png">
  <img alt="XtendLaravel" src="https://www.codelabx.ltd/assets/images/xtend-laravel/xtend-laravel-banner-light.png">
</picture>

# XtendLaravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/adam-code-labx/xtend-laravel.svg?style=flat-square)](https://packagist.org/packages/adam-code-labx/xtend-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/adam-code-labx/xtend-laravel/run-tests?label=tests)](https://github.com/adam-code-labx/xtend-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/adam-code-labx/xtend-laravel/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/adam-code-labx/xtend-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/adam-code-labx/xtend-laravel.svg?style=flat-square)](https://packagist.org/packages/adam-code-labx/xtend-laravel)

Set of tools to extend Laravel Packages.

## Example use case:
- New Laravel 9 Projects
- Existing projects (Upgrades & Refactoring)
- Package Contributors (Test out features before any planned release)

## What does this package do?

Allows developers to extend any Laravel Packages while keeping their application code clean and tidy, all the logic for package extensions in one place outside the main App namespace.
This allows for seamless upgrades and refactoring of your application.

## Installation

You can install the package via composer:

```bash
composer require adam-code-labx/xtend-laravel
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="xtend-laravel-config"
```

## Initial Setup

```bash
php artisan xtend-laravel:setup
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Adam Lee](https://github.com/adam-code-labx)
- [All Contributors](../../contributors)

## Security Vulnerabilities

If you discover a security vulnerability within Xtend Laravel, please open an issue. All security vulnerabilities will be promptly addressed.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
