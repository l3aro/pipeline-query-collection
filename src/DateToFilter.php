<?php

namespace Baro\PipelineQueryCollection;

use Baro\PipelineQueryCollection\Enums\MotionEnum;
use Illuminate\Database\Eloquent\Builder;

class DateToFilter extends BaseFilter
{
    private MotionEnum|string|null $motion;
    private $postfix = null;

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
        $postfix = $this->getPostFix() ?? config('pipeline-query-collection.date_to_postfix');
        return "{$this->detector}{$this->field}_{$postfix}";
    }

    public function setPostFix(string $postfix): self
    {
        $this->postfix = $postfix;
        return $this;
    }

    private function getPostFix(): ?string
    {
        return $this->postfix;
    }
}
