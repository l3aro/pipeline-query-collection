<?php

namespace Baro\PipelineQueryCollection;

use Baro\PipelineQueryCollection\Enums\WildcardPositionEnum;

class FieldsRelativeFilter extends BaseFilter
{
    private $wildcardPosition;

    public function __construct($field, $columns, WildcardPositionEnum|string $wildcardPosition = null)
    {
        parent::__construct();
        $this->field = $field;
        $this->searchColumns = $columns;
        if (is_null($wildcardPosition)) {
            $wildcardPosition = config('pipeline-query-collection.relative_wildcard_position', WildcardPositionEnum::BOTH);
        }
        if (!$wildcardPosition instanceof WildcardPositionEnum) {
            $wildcardPosition = WildcardPositionEnum::from($wildcardPosition);
        }
        $this->wildcardPosition = $wildcardPosition;
    }

    public static function make($field, $columns, WildcardPositionEnum|string $wildcardPosition = null)
    {
        return new self($field, $columns, $wildcardPosition);
    }

    protected function apply(): static
    {
        foreach ($this->getSearchValue() as $value) {
            foreach ($this->getSearchColumns() as $column) {
                $this->query->where($column, 'like', $this->computeSearchValue($value));
            }
        }
        return $this;
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
