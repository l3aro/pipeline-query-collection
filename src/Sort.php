<?php

namespace Baro\PipelineQueryCollection;

class Sort extends BaseSort
{
    public static function make()
    {
        return new self();
    }

    protected function apply(): static
    {
        foreach ($this->sort as $field => $direction) {
            $this->query->orderBy($field, $direction);
        }

        return $this;
    }
}
