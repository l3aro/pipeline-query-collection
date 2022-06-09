<?php

use Baro\PipelineQueryCollection\Tests\TestCase;
use Illuminate\Http\Request;

uses(TestCase::class)->in(__DIR__);

function injectRequest(array $query): void
{
    app(Request::class)->merge($query);
}
