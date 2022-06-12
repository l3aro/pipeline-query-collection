<?php

namespace Baro\PipelineQueryCollection;

class ScopeFilter extends BaseFilter
{
    public function __construct($scopeName)
    {
        parent::__construct();
        $this->field = $scopeName;
    }

    protected function apply(): static
    {
        foreach ($this->getSearchValue() as $value) {
            $this->query->{$this->field}($value);
        }

        return $this;
    }
}
