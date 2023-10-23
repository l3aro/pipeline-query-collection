<?php

namespace Baro\PipelineQueryCollection;

class SortDescending extends BaseSort
{
    public static function make(): static
    {
        return new static;
    }

    protected function apply(): static
    {
        foreach ($this->sort as $field) {
            $this->query->orderBy($field, 'desc');
        }

        return $this;
    }
}
