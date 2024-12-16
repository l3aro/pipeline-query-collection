<?php

namespace Baro\PipelineQueryCollection;

final class RangeToFilter extends BaseRangeFilter
{
    protected string $operator = '<=';

    protected function getDefaultPostfix(): string
    {
        return config('pipeline-query-collection.range_to_postfix');
    }
}