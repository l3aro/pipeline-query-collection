<?php

namespace Baro\PipelineQueryCollection\Contracts;

interface CanFilterContract
{
    public function scopeFilter($query);
    public function getFilters(): array;
}
