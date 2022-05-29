<?php

namespace Baro\PipelineQueryCollection;

use Illuminate\Database\Eloquent\Builder;

class RelationFilter extends BaseFilter
{
    private $relation;

    public function __construct($relation, $field)
    {
        parent::__construct();
        $this->relation = $relation;
        $this->field = $field;
    }

    protected function getFilterName(): string
    {
        return "{$this->detector}{$this->relation}_{$this->field}";
    }

    protected function apply(Builder $query): Builder
    {
        $searchValue = $this->getSearchValue();
        $query->whereHas($this->relation, function ($query) use ($searchValue) {
            $query->whereIn($this->getSearchColumn(), $searchValue);
        });

        return $query;
    }
}
