<?php

use Baro\PipelineQueryCollection\Tests\TestClasses\Models\TestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

it('can filter models by a bitwise flag', function () {
    TestModel::factory()->create(['type_flag' => 1]);
    TestModel::factory()->create(['type_flag' => 4]);
    injectRequest(['type_flag' => 1]);
    expect(TestModel::filter()->count())->toBe(1);
});

it('can filter models by bitwise flags', function () {
    TestModel::factory()->create(['type_flag' => 5]);
    TestModel::factory()->create(['type_flag' => 2]);
    TestModel::factory()->create(['type_flag' => 4]);
    injectRequest(['type_flag' => [1, 4]]);
    expect(TestModel::filter()->count())->toBe(1);
});
