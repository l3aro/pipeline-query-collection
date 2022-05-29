<?php

namespace Baro\PipelineQueryCollection\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

trait Filterable
{
    public function scopeFilter(Builder $query, array $criteria = null): Builder
    {
        $criteria = is_null($criteria) ? $this->getFilterCriteria() : $criteria;

        return app(Pipeline::class)
            ->send($query)
            ->through($criteria)
            ->thenReturn();
    }

    protected function getFilterCriteria(): array
    {
        if (method_exists($this, 'getFilters')) {
            return $this->getFilters();
        }

        return property_exists($this, 'filters') ? $this->filters : [];
    }
}
