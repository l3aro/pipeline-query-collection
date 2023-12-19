<?php

namespace Baro\PipelineQueryCollection;

class ScopeFilter extends BaseFilter
{
    public function __construct($scopeName)
    {
        parent::__construct();
        $this->field = $scopeName;
    }

    public static function make($scopeName)
    {
        return new self($scopeName);
    }

    protected function apply(): static
    {
        $scopeName = str($this->getSearchColumn())->camel()->toString();
        foreach ($this->getSearchValue() as $value) {
            $this->query->{$scopeName}($value);
        }

        return $this;
    }
}
