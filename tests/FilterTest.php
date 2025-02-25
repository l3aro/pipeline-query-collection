<?php

use Baro\PipelineQueryCollection\BooleanFilter;
use Baro\PipelineQueryCollection\DateFromFilter;
use Baro\PipelineQueryCollection\DateToFilter;
use Baro\PipelineQueryCollection\Enums\MotionEnum;
use Baro\PipelineQueryCollection\Enums\WildcardPositionEnum;
use Baro\PipelineQueryCollection\FieldsRelativeFilter;
use Baro\PipelineQueryCollection\RelativeFilter;
use Baro\PipelineQueryCollection\Tests\TestClasses\Models\RelatedModel;
use Baro\PipelineQueryCollection\Tests\TestClasses\Models\TestModel;

it('can filter models by bitwise flag value', function () {
    TestModel::factory()->create(['type_flag' => 1]);
    TestModel::factory()->create(['type_flag' => 4]);
    TestModel::factory()->create(['type_flag' => 5]);
    TestModel::factory()->create(['type_flag' => 7]);

    injectRequest(['type_flag' => 1]);
    expect(TestModel::filter()->count())->toBe(3);

    injectRequest(['type_flag' => [1, 4]]);
    expect(TestModel::filter()->count())->toBe(2);
});

it('can filter models by boolean value', function () {
    TestModel::factory()->count(3)->create(['is_visible' => true]);
    TestModel::factory()->count(4)->create(['is_visible' => false]);

    injectRequest(['is_visible' => true]);
    expect(TestModel::filter([
        BooleanFilter::make('is_visible'),
    ])->count())->toBe(3);

    injectRequest(['is_visible' => false]);
    expect(TestModel::filter([
        BooleanFilter::make('is_visible'),
    ])->count())->toBe(4);
});

it('can filter models by date from value', function () {
    TestModel::factory()->count(3)->create(['created_at' => '2020-01-01']);
    TestModel::factory()->count(4)->create(['created_at' => '2020-01-02']);

    injectRequest(['created_at_from' => '2020-01-03']);
    expect(TestModel::filter()->count())->toBe(0);

    injectRequest(['created_at_from' => '2020-01-02']);
    expect(TestModel::filter()->count())->toBe(4);
    expect(TestModel::filter([
        DateFromFilter::make('created_at', MotionEnum::TILL),
    ])->count())->toBe(0);

    injectRequest(['created_at_from' => '2020-01-01']);
    expect(TestModel::filter()->count())->toBe(7);
    expect(TestModel::filter([
        DateFromFilter::make('created_at', MotionEnum::TILL),
    ])->count())->toBe(4);

    injectRequest(['created_at_start' => '2020-01-01']);
    expect(TestModel::filter()->count())->toBe(7);
    expect(TestModel::filter([
        DateFromFilter::make('created_at', MotionEnum::TILL)->setPostFix('start'),
    ])->count())->toBe(4);
});

it('can filter models by date to value', function () {
    TestModel::factory()->count(4)->create(['created_at' => today()->addDays(4)]);
    TestModel::factory()->count(7)->create(['created_at' => today()->addDays(7)]);

    injectRequest(['created_at_to' => today()->addDays(4)->toDateString()]);
    expect(TestModel::filter()->count())->toBe(4);
    expect(TestModel::filter([
        DateToFilter::make('created_at', MotionEnum::TILL),
    ])->count())->toBe(0);

    injectRequest(['created_at_end' => today()->addDays(4)->toDateString()]);
    expect(TestModel::filter()->count())->toBe(4);
    expect(TestModel::filter([
        DateToFilter::make('created_at', MotionEnum::TILL)->setPostFix('end'),
    ])->count())->toBe(0);

    injectRequest(['created_at_to' => today()->addDays(7)]);
    expect(TestModel::filter()->count())->toBe(11);
    expect(TestModel::filter([
        DateToFilter::make('created_at', MotionEnum::TILL),
    ])->count())->toBe(4);

    injectRequest(['created_at_to' => today()->subDays(7)->toDateString()]);
    expect(TestModel::filter()->count())->toBe(0);
});

it('can filter models by exact value', function () {
    $now = now();
    TestModel::factory()->count(3)->create(['updated_at' => $now]);
    TestModel::factory()->count(4)->create(['updated_at' => $now->subSeconds(5)]);

    injectRequest(['updated_at' => $now]);
    expect(TestModel::filter()->count())->toBe(4);
});

it('can filter models by relationship belongsTo value', function () {
    $related1 = RelatedModel::factory()->create();
    $related2 = RelatedModel::factory()->create();
    $related3 = RelatedModel::factory()->create();
    TestModel::factory()->count(3)->create([
        'related_model_id' => $related1->id,
    ]);
    $models = TestModel::factory()->count(4)->create([
        'related_model_id' => $related2->id,
    ]);
    $models->each(function (TestModel $model) use ($related3) {
        $model->belongsToManyRelatedModels()->attach($related3->id);
    });

    injectRequest(['belongs_to_many_related_models_id' => $related3->id]);
    expect(TestModel::filter()->count())->toBe(4);
});

it('can filter models by relationship belongsToMany value', function () {
    $related3 = RelatedModel::factory()->create();
    $models = TestModel::factory()->count(4)->create();
    $models->each(function (TestModel $model) use ($related3) {
        $model->belongsToManyRelatedModels()->attach($related3->id);
    });

    injectRequest(['belongs_to_many_related_models_id' => $related3->id]);
    expect(TestModel::filter()->count())->toBe(4);
});
it('can filter models by relative value', function () {
    TestModel::factory()->create(['name' => 'Baro Nil']);

    injectRequest(['name' => 'aro']);
    expect(TestModel::filter()->count())->toBe(1);

    injectRequest(['name' => 'aro']);
    expect(TestModel::filter([
        RelativeFilter::make('name', WildcardPositionEnum::RIGHT),
    ])->count())->toBe(0);

    injectRequest(['name' => 'Baro']);
    expect(TestModel::filter([
        RelativeFilter::make('name', WildcardPositionEnum::RIGHT),
    ])->count())->toBe(1);

    injectRequest(['name' => 'ni']);
    expect(TestModel::filter([
        RelativeFilter::make('name', WildcardPositionEnum::LEFT),
    ])->count())->toBe(0);

    injectRequest(['name' => 'nil']);
    expect(TestModel::filter([
        RelativeFilter::make('name', WildcardPositionEnum::LEFT),
    ])->count())->toBe(1);
});

it('can filter models with local scope', function () {
    TestModel::factory()->create(['name' => 'Baro Nil']);
    TestModel::factory()->create(['name' => 'Billy Joe']);

    injectRequest(['search' => 'Baro']);
    expect(TestModel::filter()->count())->toBe(1);

    injectRequest(['search' => '1']);
    expect(TestModel::filter()->count())->toBe(1);

    injectRequest(['search' => 'Tom']);
    expect(TestModel::filter()->count())->toBe(0);
});

it('can filter models with prefix detect key', function () {
    TestModel::factory()->create(['name' => 'Baro Nil']);
    TestModel::factory()->create(['name' => 'Billy Joe']);

    injectRequest(['filter' => ['name' => 'Baro']]);
    expect(TestModel::filter([
        RelativeFilter::make('name')->detectBy('filter.'),
    ])->count())->toBe(1);
});

it('can filter models with custom column search', function () {
    TestModel::factory()->create(['name' => 'Baro Nil']);
    TestModel::factory()->create(['name' => 'Billy Joe']);

    injectRequest(['title' => 'Baro']);
    expect(TestModel::filter([
        RelativeFilter::make('title')->filterOn('name'),
    ])->count())->toBe(1);
});

it('can filter model with trashed', function () {
    TestModel::factory()->count(10)->create();
    TestModel::query()->limit(4)->delete();

    expect(TestModel::filter()->count())->toBe(6);

    injectRequest(['trashed' => 'with']);
    expect(TestModel::filter()->count())->toBe(10);

    injectRequest(['trashed' => 'only']);
    expect(TestModel::filter()->count())->toBe(4);
});

it('can filter model using fixed value', function () {
    TestModel::factory()->create(['name' => 'Baro Nil']);
    TestModel::factory()->create(['name' => 'Billy Joe']);

    injectRequest(['title' => 'John']);
    expect(TestModel::filter([
        RelativeFilter::make('title')->filterOn('name'),
    ])->count())->toBe(0);

    expect(TestModel::filter([
        RelativeFilter::make('title')->filterOn('name')->value('Baro'),
    ])->count())->toBe(1);
});

it('can filter many columns with one field', function () {
    TestModel::factory()->create(['name' => 'Baro Nil']);
    TestModel::factory()->create(['name' => 'Baro Joe']);
    TestModel::factory()->create(['name' => 'Billy Nil']);

    injectRequest(['name' => 'Baro']);

    expect(TestModel::filter([
        FieldsRelativeFilter::make('name', ['name', 'title']),
    ])->count())->toBe(2);
});
