<?php

namespace Baro\PipelineQueryCollection\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

trait Sortable
{
    public function scopeSort(Builder $query, array $criteria = null): Builder
    {
        $criteria = is_null($criteria) ? $this->sortCriteria() : $criteria;

        return app(Pipeline::class)
            ->send($query)
            ->through($criteria)
            ->thenReturn();
    }

    public function sortCriteria(): array
    {
        if (method_exists($this, 'getSorts')) {
            return $this->getSorts();
        }

        return [];
    }
}
