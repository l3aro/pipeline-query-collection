<?php

namespace Baro\PipelineQueryCollection\Database\Factories;

use Baro\PipelineQueryCollection\Tests\TestClasses\Models\TestModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestModelFactory extends Factory
{
    protected $model = TestModel::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'type_flag' => $this->faker->randomElement([1, 2, 4]),
            'is_visible' => $this->faker->boolean,
            'created_at' => $this->faker->dateTime,
        ];
    }
}
