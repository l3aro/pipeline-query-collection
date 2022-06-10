<?php

use Baro\PipelineQueryCollection\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

uses(TestCase::class)->in(__DIR__);

function injectRequest(array $query): void
{
    app(Request::class)->merge($query);
}

function assertQueryExecuted(string $query)
{
    $queries = array_map(function ($queryLogItem) {
        return $queryLogItem['query'];
    }, DB::getQueryLog());
    expect($queries)->toContain($query);
}
