<?php

namespace Baro\PipelineQueryCollection;

class BooleanFilter extends BaseFilter
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
        foreach ($this->getSearchValue() as $value) {
            $this->query->where($this->getSearchColumn(), $value ? true : false);
        }

        return $this;
    }
}
