<?php

namespace Baro\PipelineQueryCollection\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

trait Filterable
{
    public function scopeFilter(Builder $query, array $criteria = null): Builder
    {
        $criteria = is_null($criteria) ? $this->filterCriteria() : $criteria;

        return app(Pipeline::class)
            ->send($query)
            ->through($criteria)
            ->thenReturn();
    }

    public function filterCriteria(): array
    {
        if (method_exists($this, 'getFilters')) {
            return $this->getFilters();
        }

        return [];
    }
}
