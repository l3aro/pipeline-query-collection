<?php

namespace Baro\PipelineQueryCollection\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Baro\PipelineQueryCollection\PipelineQueryCollectionServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TestCase extends Orchestra
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Baro\\PipelineQueryCollection\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function setUpDatabase(Application $app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedTinyInteger('type_flag')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('related_model_id')->nullable();
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('related_models', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('pivot_table', function (Blueprint $table) {
            $table->unsignedInteger('test_model_id');
            $table->unsignedInteger('related_model_id');
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            PipelineQueryCollectionServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        /*
        $migration = include __DIR__.'/../database/migrations/create_pipeline-query-collection_table.php.stub';
        $migration->up();
        */
    }
}
