<?php

namespace Baro\PipelineQueryCollection\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface CanFilterContract
{
    public function scopeFilter(Builder $query, array $criteria = null): Builder;

    public function getFilters(): array;
}
