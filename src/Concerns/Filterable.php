<?php

namespace Baro\PipelineQueryCollection\Concerns;

use Illuminate\Pipeline\Pipeline;

trait Filterable
{
    public function scopeFilter($query)
    {
        return app(Pipeline::class)
            ->send($query)
            ->through($this->getFilters());
    }
}
