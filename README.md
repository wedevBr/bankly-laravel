# Bankly Laravel API Wrapper

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wedevbr/bankly-laravel.svg?style=flat-square)](https://packagist.org/packages/wedevbr/bankly-laravel)
[![Build Status](https://img.shields.io/travis/wedevbr/bankly-laravel/master.svg?style=flat-square)](https://travis-ci.org/wedevbr/bankly-laravel)
[![Quality Score](https://img.shields.io/scrutinizer/g/wedevbr/bankly-laravel.svg?style=flat-square)](https://scrutinizer-ci.com/g/wedevbr/bankly-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/wedevbr/bankly-laravel.svg?style=flat-square)](https://packagist.org/packages/wedevbr/bankly-laravel)

This package is an **UNOFFICIAL** API Wrapper for [Bankly/Acesso API](https://bankly.readme.io/).

## Installation

You can install the package via composer:

```bash
composer require wedevbr/bankly-laravel
```

After install, just publish your config files:
```bash
php artisan vendor:publish --provider="WeDevBr\Bankly\BanklyServiceProvider"
```

## Usage
First you need to set up your credentials. Define yours `BANKLY_CLIENT_SECRET` and `BANKLY_CLIENT_ID` at .env file.

Tip: If you are running on Staging, you can set up Bankly endpoints also. Just define `BANKLY_LOGIN_URL` and `BANKLY_API_URL` variables.

Then, finally use:

```php
//This is your statement
$statement = \Bankly::getStatement('0001', '123456');
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email adeildo@wedev.software instead of using the issue tracker.

## Credits

- [We Dev Tecnologia LTDA](https://github.com/wedevbr)
- [Adeildo Amorim](https://github.com/adeildo-jr)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
