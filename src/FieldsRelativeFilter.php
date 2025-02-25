<?php

namespace Baro\PipelineQueryCollection;

use Baro\PipelineQueryCollection\Enums\WildcardPositionEnum;

class FieldsRelativeFilter extends BaseFilter
{
    private $wildcardPosition;

    public function __construct($field, $columns, WildcardPositionEnum|string|null $wildcardPosition = null)
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

    public static function make($field, $columns, WildcardPositionEnum|string|null $wildcardPosition = null)
    {
        return new self($field, $columns, $wildcardPosition);
    }

    protected function getSearchColumns()
    {
        return $this->searchColumns ?? $this->field;
    }

    protected function apply(): static
    {
        foreach ($this->getSearchValue() as $value) {
            $this->query->whereNested(function ($query) use ($value) {
                foreach ($this->getSearchColumns() as $column) {
                    $query->orWhere($column, 'like', $this->computeSearchValue($value));
                }
            });
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
