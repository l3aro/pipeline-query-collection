<?php

namespace Baro\PipelineQueryCollection\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface CanSortContract
{
    public function scopeSort(Builder $query, array $criteria = null): Builder;

    public function getSorts(): array;
}
