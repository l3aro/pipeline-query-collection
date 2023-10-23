<?php

namespace Baro\PipelineQueryCollection;

use Illuminate\Support\Str;

class RelationFilter extends BaseFilter
{
    private $relation;

    public function __construct($relation, $field)
    {
        parent::__construct();
        $this->relation = $relation;
        $this->field = $field;
    }

    public static function make($relation, $field): static
    {
        return new static($relation, $field);
    }

    protected function getFilterName(): string
    {
        return "{$this->detector}{$this->relation}_{$this->field}";
    }

    protected function apply(): static
    {
        $searchValue = $this->getSearchValue();
        $this->relation = Str::camel($this->relation);
        $this->query->whereHas($this->relation, function ($query) use ($searchValue) {
            $query->whereIn($this->getSearchColumn(), $searchValue);
        });

        return $this;
    }
}
