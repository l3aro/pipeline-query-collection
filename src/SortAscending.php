<?php

namespace Baro\PipelineQueryCollection;

class SortAscending extends BaseSort
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function apply(): static
    {
        foreach ($this->sort as $field) {
            $this->query->orderBy($field, 'asc');
        }

        return $this;
    }
}
