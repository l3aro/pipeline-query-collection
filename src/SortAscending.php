<?php

namespace Baro\PipelineQueryCollection;

class SortAscending extends BaseSort
{
    public static function make()
    {
        return new self();
    }

    protected function apply(): static
    {
        foreach ($this->sort as $field) {
            $this->query->orderBy($field, 'asc');
        }

        return $this;
    }
}
