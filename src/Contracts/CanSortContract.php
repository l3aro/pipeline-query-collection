<?php

namespace Baro\PipelineQueryCollection\Contracts;

interface CanSortContract
{
    public function scopeSort($query);
    public function getSorts(): array;
}
