<?php

use Baro\PipelineQueryCollection\Sort;
use Baro\PipelineQueryCollection\SortAscending;
use Baro\PipelineQueryCollection\SortDescending;
use Baro\PipelineQueryCollection\Tests\TestClasses\Models\TestModel;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\assertEquals;

beforeEach(function () {
    DB::enableQueryLog();
    TestModel::factory()->count(10)->create();
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
    $expected = $query->pluck('id')->toArray();
    $actual = TestModel::query()->orderBy($column, $direction)->pluck('id')->values()->toArray();
    assertEquals($expected, $actual);
})->with('columns')->with('directions');

it('can sort by a single column', function (string $column, string $direction) {
    injectRequest(['sort' => [$column => $direction]]);
    $query = TestModel::sort();
    $expected = $query->pluck('id')->toArray();
    $actual = TestModel::query()->orderBy($column, $direction)->pluck('id')->values()->toArray();
    assertEquals($expected, $actual);
})->with('columns')->with('directions');

it('can sort by multiple columns', function () {
    injectRequest(['sort' => ['name' => 'asc', 'id' => 'desc']]);
    $query = TestModel::sort();
    $expected = $query->pluck('id')->toArray();
    $actual = TestModel::query()->orderBy('name', 'asc')->orderBy('id', 'desc')->pluck('id')->values()->toArray();
    assertEquals($expected, $actual);
});

it('can sort ascending by a single column', function (string $column) {
    injectRequest(['sort' => [$column]]);
    $query = TestModel::sort([
        SortAscending::make(),
    ]);
    $expected = $query->pluck('id')->toArray();
    $actual = TestModel::query()->orderBy($column, 'asc')->pluck('id')->values()->toArray();
    assertEquals($expected, $actual);
})->with('columns');

it('can sort ascending by multiple columns', function () {
    injectRequest(['sort' => ['name', 'id']]);
    $query = TestModel::sort([
        SortAscending::make(),
    ]);
    $expected = $query->pluck('id')->toArray();
    $actual = TestModel::query()->orderBy('name', 'asc')->orderBy('id', 'asc')->pluck('id')->values()->toArray();
    assertEquals($expected, $actual);
});

it('can sort descending by a single column', function (string $column) {
    injectRequest(['sort' => [$column]]);
    $query = TestModel::sort([
        SortDescending::make(),
    ]);
    $expected = $query->pluck('id')->toArray();
    $actual = TestModel::query()->orderBy($column, 'desc')->pluck('id')->values()->toArray();
    assertEquals($expected, $actual);
})->with('columns');

it('can sort descending by multiple columns', function () {
    injectRequest(['sort' => ['name', 'id']]);
    $query = TestModel::sort([
        SortDescending::make(),
    ]);
    $expected = $query->pluck('id')->toArray();
    $actual = TestModel::query()->orderBy('name', 'desc')->orderBy('id', 'desc')->pluck('id')->values()->toArray();
    assertEquals($expected, $actual);
});
