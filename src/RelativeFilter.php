<?php

namespace Baro\PipelineQueryCollection;

use Baro\PipelineQueryCollection\Enums\WildcardPositionEnum;
use Illuminate\Database\Eloquent\Builder;

class RelativeFilter extends BaseFilter
{
    private $wildcardPosition;

    public function __construct($field, WildcardPositionEnum|string $wildcardPosition = null)
    {
        parent::__construct();
        $this->filterOn($field);
        if (is_null($wildcardPosition)) {
            $wildcardPosition = config('pipeline-query-collection.relative_wildcard_position');
        }
        if (!$wildcardPosition instanceof WildcardPositionEnum) {
            $wildcardPosition = WildcardPositionEnum::from($wildcardPosition);
        }
        $this->wildcardPosition = $wildcardPosition;
    }

    protected function apply(Builder $query): Builder
    {
        foreach ($this->getSearchValue() as $value) {
            $query->where($this->getSearchColumn(), 'like', $this->computeSearchValue($value));
        }
        return $query;
    }

    private function computeSearchValue($value)
    {
        return match ($this->wildcardPosition) {
            WildcardPositionEnum::RIGHT => "$value%",
            WildcardPositionEnum::LEFT => "%$value",
            default => "%$value%",
        };
    }
}
