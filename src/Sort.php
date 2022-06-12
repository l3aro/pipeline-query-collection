<?php

namespace Baro\PipelineQueryCollection;

class Sort extends BaseSort
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function apply(): static
    {
        foreach ($this->sort as $field => $direction) {
            $this->query->orderBy($field, $direction);
        }

        return $this;
    }
}
