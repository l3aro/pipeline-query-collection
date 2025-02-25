<?php

namespace Baro\PipelineQueryCollection;

final class RangeFromFilter extends BaseRangeFilter
{
    protected string $operator = '>=';

    protected function getDefaultPostfix(): string
    {
        return config('pipeline-query-collection.range_from_postfix');
    }
}
