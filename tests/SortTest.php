<?php

use Baro\PipelineQueryCollection\Sort;
use Baro\PipelineQueryCollection\SortAscending;
use Baro\PipelineQueryCollection\SortDescending;
use Baro\PipelineQueryCollection\Tests\TestClasses\Models\TestModel;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    DB::enableQueryLog();
    TestModel::factory()->count(10)->create();
    $this->baseQuery = 'select * from "test_models" where "test_models"."deleted_at" is null';
});

dataset('directions', [
    'asc',
    'desc',
]);

dataset('columns', [
    'name',
    'id',
]);

it('can sort using fixed value', function (string $column, string $direction) {
    $query = TestModel::sort([
        Sort::make()->value([$column => $direction]),
    ]);
    $query->get();
    assertQueryExecuted($this->baseQuery . ' order by "' . $column . '" ' . $direction);
})->with('columns')->with('directions');

it('can sort by a single column', function (string $column, string $direction) {
    injectRequest(['sort' => [$column => $direction]]);
    $query = TestModel::sort();
    $query->get();
    assertQueryExecuted($this->baseQuery . ' order by "' . $column . '" ' . $direction);
})->with('columns')->with('directions');

it('can sort by multiple columns', function () {
    injectRequest(['sort' => ['name' => 'asc', 'id' => 'desc']]);
    $query = TestModel::sort();
    $query->get();
    assertQueryExecuted($this->baseQuery . ' order by "name" asc, "id" desc');
});

it('can sort ascending by a single column', function (string $column) {
    injectRequest(['sort' => [$column]]);
    $query = TestModel::sort([
        SortAscending::make(),
    ]);
    $query->get();
    assertQueryExecuted($this->baseQuery . ' order by "' . $column . '" asc');
})->with('columns');

it('can sort ascending by multiple columns', function () {
    injectRequest(['sort' => ['name', 'id']]);
    $query = TestModel::sort([
        SortAscending::make(),
    ]);
    $query->get();
    assertQueryExecuted($this->baseQuery . ' order by "name" asc, "id" asc');
});

it('can sort descending by a single column', function (string $column) {
    injectRequest(['sort' => [$column]]);
    $query = TestModel::sort([
        SortDescending::make(),
    ]);
    $query->get();
    assertQueryExecuted($this->baseQuery . ' order by "' . $column . '" desc');
})->with('columns');

it('can sort descending by multiple columns', function () {
    injectRequest(['sort' => ['name', 'id']]);
    $query = TestModel::sort([
        SortDescending::make(),
    ]);
    $query->get();
    assertQueryExecuted($this->baseQuery . ' order by "name" desc, "id" desc');
});
