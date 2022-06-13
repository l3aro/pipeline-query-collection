
## A query database collection for use with Laravel Pipeline

[![Latest Version on Packagist](https://img.shields.io/packagist/v/l3aro/pipeline-query-collection.svg?style=flat-square)](https://packagist.org/packages/l3aro/pipeline-query-collection)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/l3aro/pipeline-query-collection/run-tests?label=tests)](https://github.com/l3aro/pipeline-query-collection/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/l3aro/pipeline-query-collection.svg?style=flat-square)](https://packagist.org/packages/l3aro/pipeline-query-collection)

This package contains a collection of class that can be used with Laravel Pipeline. Let's see below queries:

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

As you all can see,  it's obviously that filter conditions will continue to grow as well as the duplication of same filter for other queries. We can use Laravel Pipeline combine with some pre-made queries to refactor this

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

## Table of Contents

* [A query database collection for use with Laravel Pipeline](#a-query-database-collection-for-use-with-laravel-pipeline)
* [Table of Contents](#table-of-contents)
* [Installation](#installation)
* [Usage](#usage)
    * [Preparing your model](#preparing-your-model)
    * [Feature](#feature)
        * [Bitwise filter](#bitwise-filter)
        * [Boolean filter](#boolean-filter)
        * [Date From filter](#date-from-filter)
        * [Date To filter](#date-to-filter)
        * [Exact filter](#exact-filter)
        * [Relation filter](#relation-filter)
        * [Relative filter](#relative-filter)
        * [Scope filter](#scope-filter)
        * [Trash filter](#trash-filter)
        * [Sort](#sort)
    * [Detector](#detector)
    * [Custom search column](#custom-search-column)
    * [Extend filter](#extend-filter)
* [Testing](#testing)
* [Contributing](#contributing)
* [Security Vulnerabilities](#security-vulnerabilities)
* [Credits](#credits)
* [License](#license)

## Installation

Install the package via composer:

```bash
composer require l3aro/pipeline-query-collection
```

Optionally, you can publish the config file with:

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
### Preparing your model
To use this collection with a model, you should implement the following interface and trait:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Baro\PipelineQueryCollection\Concerns\Filterable;
use Baro\PipelineQueryCollection\Contracts\CanFilterContract;

class YourModel extends Model implements CanFilterContract
{
    use Filterable;

    public function getFilters(): array
    {
        return [
            // the filter and sorting that your model need
        ];
    }
}
```

After setup your model, you can use scope filter on your model like this

```php
YourModel::query()->filter()->get();
```

You can also override the predefined filter lists in your model like this

```php
YourModel::query()->filter([
    // the custom filter and sorting that your model need
])
->paginate();
```

### Feature
Here the use all filter and sort in the collection

#### Bitwise filter

```php
use Baro\PipelineQueryCollection\BitwiseFilter;

// users?permission[0]=2&permission[1]=4
User::query()->filter([
    new BitwiseFilter('permission'), // where permission & 6 = 6
]);
```

#### Boolean filter

```php
use Baro\PipelineQueryCollection\BooleanFilter;

// users?is_admin=1
User::query()->filter([
    new BooleanFilter('is_admin'), // where is_admin = 1
]);
```

#### Date From filter
```php
use Baro\PipelineQueryCollection\DateFromFilter;
use Baro\PipelineQueryCollection\Enums\MotionEnum;

// users?updated_at_from=2022-05-31
User::query()->filter([
    new DateFromFilter('updated_at'), // where updated_at >= 2022-05-31
    new DateFromFilter('updated_at', MotionEnum::TILL), // where updated_at > 2022-05-31
    // you can config default motion behavior and the postfix `from` in the config file
]);
```

#### Date To filter
```php
use Baro\PipelineQueryCollection\DateToFilter;
use Baro\PipelineQueryCollection\Enums\MotionEnum;

// users?updated_at_to=2022-05-31
User::query()->filter([
    new DateToFilter('updated_at'), // where updated_at <= 2022-05-31
    new DateToFilter('updated_at', MotionEnum::TILL), // where updated_at < 2022-05-31
    // you can config default motion behavior and the postfix `to` in the config file
]);
```

#### Exact filter
```php
use Baro\PipelineQueryCollection\ExactFilter;

// users?id=4
User::query()->filter([
    new ExactFilter('id'), // where id = 4
]);
```

#### Relation filter
```php
use Baro\PipelineQueryCollection\RelationFilter;

// users?roles_id[0]=1&roles_id[1]=4
User::query()->filter([
    new RelationFilter('roles', 'id'), // where roles.id in(1,4)
]);
```

#### Relative filter

```php
use Baro\PipelineQueryCollection\RelativeFilter;
use Baro\PipelineQueryCollection\Enums\WildcardPositionEnum;

// users?name=Baro
User::query()->filter([
    new RelativeFilter('name'), // where('name', 'like', "%Baro%")
    new RelativeFilter('name', WildcardPositionEnum::LEFT), // where('name', 'like', "%Baro")
    new RelativeFilter('name', WildcardPositionEnum::RIGHT), // where('name', 'like', "Baro%")
]);
```

#### Scope filter
```php
// users?search=Baro

// User.php
public function scopeSearch(Builder $query, string $keyword)
{
    return $query->where(function (Builder $query)  use ($keyword) {
        $query->where('id', $keyword)
            ->orWhere('name', 'like', "%{$keyword}%");
    });
}

// Query
use Baro\PipelineQueryCollection\ScopeFilter;

User::query()->filter([
    new ScopeFilter('search'), // where (`id` = 'Baro' or `name` like '%Baro%')
]);
```

#### Trash filter

When using Laravel's [soft delete](https://laravel.com/docs/master/eloquent#querying-soft-deleted-models), you can use the pipe `TrashFilter`
 to query these models. The default query name is `trashed`, and filters responds to particular values:
 * `with`: the query should be `?trashed=with` to include soft deleted records to the result set
 * `only`: the query should be `?trashed=only` to return only soft deleted records to the result set
 * any other value, or completely remove `trashed` from request query will return only records that are not soft deleted in the result set

 You can change query name `trashed` by passing your custom name to the `TrashFilter` constructor
 ```php
use Baro\PipelineQueryCollection\TrashFilter;


// ?removed=only
User::query()->filter([
    new TrashFilter('removed'), // where `deleted_at` is not null
]);
```

#### Sort
```php
use Baro\PipelineQueryCollection\ScopeFilter;

// users?sort[name]=asc&sort[permission]=desc
User::query()->filter([
    new Sort(), //  order by `name` asc, `permission` desc
]);
```

### Detector
Sometimes, you want to setup your request with a prefix like `filter.`. You can config every pipe that have it
```php
use Baro\PipelineQueryCollection\ExactFilter;

// users?filter[id]=4&filter[permission][0]=1&filter[permission][1]=4
User::query()->filter([
    (new ExactFilter('id'))->detectBy('filter.'), // where id = 4
    (new BitwiseFilter('permission'))->detectBy('filter.'), // where permission & 5 = 5
]);
```

Or, you can define it globally
```php
// users?filter[id]=4&filter[permission][0]=1&filter[permission][1]=4

// .env
PIPELINE_QUERY_COLLECTION_DETECT_KEY="filter."

// Query
User::query()->filter([
    new ExactFilter('id'), // where id = 4
    new BitwiseFilter('permission'), // where permission & 5 = 5
]);
```

### Custom search column

Sometimes, your request field is not the same with column name. For example, in your database you have column `respond` and want to perform some query against it, but for some reasons, your request query is `reply` instead of `respond`.

```php
// users?reply=baro

User::query()->filter([
    (new RelativeFilter('reply'))->filterOn('respond'), // where respond like '%baro%'
]);
```

### Extend filter
Yeah, you are free to use your own pipe. Take a look at some of my filters. All of them extends `BaseFilter` to have some useful properties and functions.

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [l3aro](https://github.com/l3aro)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
