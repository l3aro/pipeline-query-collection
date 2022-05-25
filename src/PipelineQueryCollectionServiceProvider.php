<?php

namespace Baro\PipelineQueryCollection;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Baro\PipelineQueryCollection\Commands\PipelineQueryCollectionCommand;

class PipelineQueryCollectionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('pipeline-query-collection')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_pipeline-query-collection_table')
            ->hasCommand(PipelineQueryCollectionCommand::class);
    }
}
