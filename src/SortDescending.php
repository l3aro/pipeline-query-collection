<?php

namespace Baro\PipelineQueryCollection;

class SortDescending extends BaseSort
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function apply(): static
    {
        foreach ($this->sort as $field) {
            $this->query->orderBy($field, 'desc');
        }

        return $this;
    }
}
