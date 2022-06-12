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
        foreach ($this->getSearchValue() as $value) {
            $this->query->where($this->getSearchColumn(), $value);
        }
        return $this;
    }
}
