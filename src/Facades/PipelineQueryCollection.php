<?php

namespace Baro\PipelineQueryCollection\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Baro\PipelineQueryCollection\PipelineQueryCollection
 */
class PipelineQueryCollection extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pipeline-query-collection';
    }
}
