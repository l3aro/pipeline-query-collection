<?php

namespace Baro\PipelineQueryCollection\Concerns;

use Illuminate\Pipeline\Pipeline;

trait Sortable
{
    public function scopeSort($query)
    {
        return app(Pipeline::class)
            ->send($query)
            ->through($this->getSorts());
    }
}
