<?php

namespace Baro\PipelineQueryCollection\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

trait Filterable
{
    public function scopeFilter(Builder $query, array $criteria = null): Builder
    {
        $criteria = is_null($criteria) ? $this->getFilters() : $criteria;

        return app(Pipeline::class)
            ->send($query)
            ->through($criteria)
            ->thenReturn();
    }
}
