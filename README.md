# LangGraph Platform

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jasontame/langgraph-platform-php.svg?style=flat-square)](https://packagist.org/packages/jasontame/langgraph-platform-php)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jasontame/langgraph-platform-php/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jasontame/langgraph-platform-php/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jasontame/langgraph-platform-php/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jasontame/langgraph-platform-php/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jasontame/langgraph-platform-php.svg?style=flat-square)](https://packagist.org/packages/jasontame/langgraph-platform-php)

An unoffical PHP SDK for interacting with the LangGraph Platform API.

## Installation

You can install the package via composer:

```bash
composer require jasontame/langgraph-platform-php
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="langgraph-platform-php-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="langgraph-platform-php-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="langgraph-platform-php-views"
```

## Usage

```php
$langgraphPlatform = new Jason Tame\LangGraphPlatform();
echo $langgraphPlatform->echoPhrase('Hello, Jason Tame!');
```

## Testing

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

- [Jason Tame](https://github.com/jasontame)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
