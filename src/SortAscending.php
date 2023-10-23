<?php

namespace Baro\PipelineQueryCollection;

final class SortAscending extends BaseSort
{
    public static function make(): static
    {
        return new self;
    }

    protected function apply(): static
    {
        foreach ($this->sort as $field) {
            $this->query->orderBy($field, 'asc');
        }

        return $this;
    }
}
