
# A query database collection for use with Laravel Pipeline

[![Latest Version on Packagist](https://img.shields.io/packagist/v/l3aro/pipeline-query-collection.svg?style=flat-square)](https://packagist.org/packages/l3aro/pipeline-query-collection)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/l3aro/pipeline-query-collection/run-tests?label=tests)](https://github.com/l3aro/pipeline-query-collection/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/l3aro/pipeline-query-collection/Check%20&%20fix%20styling?label=code%20style)](https://github.com/l3aro/pipeline-query-collection/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/l3aro/pipeline-query-collection.svg?style=flat-square)](https://packagist.org/packages/l3aro/pipeline-query-collection)

A query database collection for use with Laravel Pipeline

## Installation

You can install the package via composer:

```bash
composer require l3aro/pipeline-query-collection
```

You can publish and run the migrations with:

You can publish the config file with:

```bash
php artisan vendor:publish --tag="pipeline-query-collection-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$pipelineQueryCollection = new Baro\PipelineQueryCollection();
echo $pipelineQueryCollection->echoPhrase('Hello, Baro!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [l3aro](https://github.com/l3aro)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
