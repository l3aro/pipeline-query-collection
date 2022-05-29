<?php

namespace Baro\PipelineQueryCollection;

use Baro\PipelineQueryCollection\Enums\MotionEnum;
use Illuminate\Database\Eloquent\Builder;

class DateToFilter extends BaseFilter
{
    private $motion;

    public function __construct($field = 'created_at', MotionEnum|string $motion = null)
    {
        parent::__construct();
        $this->field = $field;
        if (is_null($motion)) {
            $motion = config('pipeline-query-collection.date_motion');
        }
        if (!$motion instanceof MotionEnum) {
            $motion = MotionEnum::from($motion);
        }
        $this->motion = $motion;
    }

    protected function apply(Builder $query): Builder
    {
        $operator = $this->motion === MotionEnum::FIND ? '<=' : '<';
        foreach ($this->getSearchValue() as $value) {
            $query->where($this->getSearchColumn(), $operator, $value);
        }
        return $query;
    }

    protected function getFilterName(): string
    {
        $postfix = config('pipeline-query-collection.date_from_postfix');
        return "{$this->detector}{$this->field}_{$postfix}";
    }
}
