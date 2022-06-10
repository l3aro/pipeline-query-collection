<?php

namespace Baro\PipelineQueryCollection\Database\Factories;

use Baro\PipelineQueryCollection\Tests\TestClasses\Models\RelatedModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class RelatedModelFactory extends Factory
{
    protected $model = RelatedModel::class;

    public function definition()
    {
        return [];
    }
}
