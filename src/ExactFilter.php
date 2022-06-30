<?php

namespace Baro\PipelineQueryCollection;

class ExactFilter extends BaseFilter
{
    public function __construct($field)
    {
        parent::__construct();
        $this->field = $field;
    }

    protected function apply(): static
    {
        $this->query->whereIn($this->getSearchColumn(), $this->getSearchValue());
        return $this;
    }
}
