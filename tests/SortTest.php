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
    $query = TestModel::sort();
    $query->get();
    assertQueryExecuted($query->toSql());

    injectRequest(['sort' => ['name' => 'desc']]);
    $query = TestModel::sort();
    $query->get();
    assertQueryExecuted($query->toSql());
});

it('can sort by multiple columns', function () {
    injectRequest(['sort' => ['name' => 'asc', 'id' => 'desc']]);
    $query = TestModel::sort();
    $query->get();
    assertQueryExecuted($query->toSql());
});

it('can sort ascending by a single column', function () {
    injectRequest(['sort' => ['name']]);
    $query = TestModel::sort([
        SortAscending::make(),
    ]);
    $query->get();
    assertQueryExecuted($query->toSql());
});

it('can sort ascending by multiple columns', function () {
    injectRequest(['sort' => ['name', 'id']]);
    $query = TestModel::sort([
        SortAscending::make(),
    ]);
    $query->get();
    assertQueryExecuted($query->toSql());
});

it('can sort descending by a single column', function () {
    injectRequest(['sort' => ['name']]);
    $query = TestModel::sort([
        SortDescending::make(),
    ]);
    $query->get();
    assertQueryExecuted($query->toSql());
});

it('can sort descending by multiple columns', function () {
    injectRequest(['sort' => ['name', 'id']]);
    $query = TestModel::sort([
        SortDescending::make(),
    ]);
    $query->get();
    assertQueryExecuted($query->toSql());
});
