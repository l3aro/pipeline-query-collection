<?php

namespace Baro\PipelineQueryCollection;

class ExactFilter extends BaseFilter
{
    public function __construct($field)
    {
        parent::__construct();
        $this->field = $field;
    }

    public static function make($field)
    {
        return new self($field);
    }

    protected function apply(): static
    {
        $this->query->whereIn($this->getSearchColumn(), $this->getSearchValue());

        return $this;
    }
}
