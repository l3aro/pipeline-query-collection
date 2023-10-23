<?php

namespace Baro\PipelineQueryCollection;

class SortAscending extends BaseSort
{
    public static function make(): static
    {
        return new static;
    }

    protected function apply(): static
    {
        foreach ($this->sort as $field) {
            $this->query->orderBy($field, 'asc');
        }

        return $this;
    }
}
