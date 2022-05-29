<?php

namespace Baro\PipelineQueryCollection;

use Illuminate\Database\Eloquent\Builder;

class ScopeFilter extends BaseFilter
{
    public function __construct($scopeName)
    {
        parent::__construct();
        $this->field = $scopeName;
    }

    protected function apply(Builder $query): Builder
    {
        foreach ($this->getSearchValue() as $value) {
            $query->{$this->field}($value);
        }

        return $query;
    }
}
