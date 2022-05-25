
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

You can publish the config file with:

```bash
php artisan vendor:publish --tag="pipeline-query-collection-config"
```

This is the contents of the published config file:

```php
return [
    // key to detect param to filter
    'detect_key' => env('PIPELINE_QUERY_COLLECTION_DETECT_KEY', ''),

    // type of postfix for date filters
    'date_from_postfix' => env('PIPELINE_QUERY_COLLECTION_DATE_FROM_POSTFIX', 'from'),
    'date_to_postfix' => env('PIPELINE_QUERY_COLLECTION_DATE_TO_POSTFIX', 'to'),

    // default motion for date filters
    'date_motion' => env('PIPELINE_QUERY_COLLECTION_DATE_MOTION', 'find'),
];
```

## Usage

This package contains a collection of class that can be used with Laravel Pipeline.

Let's see below queries

```php
// users?name=Baro&is_admin=1&created_at_from=2022-06-01&created_at_to=2022-06-31
$users = User::query()
    ->when($request->name ?? null, function($query, $name) {
        $query->where('name', 'like', "%$name%");
    })
    ->when($request->is_admin ?? null, function($query, $isAdmin) {
        $query->where('is_admin', $isAdmin ? 1 : 0);
    })
    ->when($request->created_at_from ?? null, function($query, $date) {
        $query->where('created_at', '>=', $date);
    })
    ->when($request->created_at_to ?? null, function($query, $date) {
        $query->where('created_at', '<=', $date);
    })
    ->get();
```

As you all can see,  it's obviously that filters conditions will continue to grow as well as the duplication of same filter for other queries. We can use Laravel Pipeline combine with some pre-made queries to refactor this

```php
use Baro\PipelineQueryCollection;

// users?name=Baro&is_admin=1&created_at_from=2022-06-01&created_at_to=2022-06-31
$users = Users::query()->filter([
    new PipelineQueryCollection\RelativeFilter('name'),
    new PipelineQueryCollection\BooleanFilter('is_admin'),
    new PipelineQueryCollection\DateFromFilter('created_at'),
    new PipelineQueryCollection\DateToFilter('created_at'),
])
->get();
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
