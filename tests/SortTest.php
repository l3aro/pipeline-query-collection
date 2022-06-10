<?php

use Baro\PipelineQueryCollection\SortAscending;
use Baro\PipelineQueryCollection\SortDescending;
use Baro\PipelineQueryCollection\Tests\TestClasses\Models\TestModel;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    DB::enableQueryLog();
    TestModel::factory()->count(10)->create();
});

it('can sort by a single column', function () {
    injectRequest(['sort' => ['name' => 'asc']]);
    TestModel::sort()->get();
    assertQueryExecuted('select * from `test_models` where `test_models`.`deleted_at` is null order by `name` asc');

    injectRequest(['sort' => ['name' => 'desc']]);
    TestModel::sort()->get();
    assertQueryExecuted('select * from `test_models` where `test_models`.`deleted_at` is null order by `name` desc');
});

it('can sort by multiple columns', function () {
    injectRequest(['sort' => ['name' => 'asc', 'id' => 'desc']]);
    TestModel::sort()->get();
    assertQueryExecuted('select * from `test_models` where `test_models`.`deleted_at` is null order by `name` asc, `id` desc');
});

it('can sort ascending by a single column', function () {
    injectRequest(['sort' => ['name']]);
    TestModel::sort([
        new SortAscending,
    ])->get();
    assertQueryExecuted('select * from `test_models` where `test_models`.`deleted_at` is null order by `name` asc');
});

it('can sort ascending by multiple columns', function () {
    injectRequest(['sort' => ['name', 'id']]);
    TestModel::sort([
        new SortAscending,
    ])->get();
    assertQueryExecuted('select * from `test_models` where `test_models`.`deleted_at` is null order by `name` asc, `id` asc');
});

it('can sort descending by a single column', function () {
    injectRequest(['sort' => ['name']]);
    TestModel::sort([
        new SortDescending,
    ])->get();
    assertQueryExecuted('select * from `test_models` where `test_models`.`deleted_at` is null order by `name` desc');
});

it('can sort descending by multiple columns', function () {
    injectRequest(['sort' => ['name', 'id']]);
    TestModel::sort([
        new SortDescending,
    ])->get();
    assertQueryExecuted('select * from `test_models` where `test_models`.`deleted_at` is null order by `name` desc, `id` desc');
});
