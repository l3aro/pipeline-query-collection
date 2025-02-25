<?php

namespace Baro\PipelineQueryCollection;

use Baro\PipelineQueryCollection\Enums\MotionEnum;

class DateFromFilter extends BaseFilter
{
    protected MotionEnum|string|null $motion;

    protected $postfix = null;

    public function __construct($field = 'created_at', MotionEnum|string|null $motion = null)
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

    public static function make($field = 'created_at', MotionEnum|string|null $motion = null)
    {
        return new self($field, $motion);
    }

    protected function apply(): static
    {
        $operator = $this->motion === MotionEnum::FIND ? '>=' : '>';
        $action = $this->getAction();
        foreach ($this->getSearchValue() as $value) {
            $this->query->$action($this->getSearchColumn(), $operator, $value);
        }

        return $this;
    }

    private function getAction(): string
    {
        return match ($this->getDriverName()) {
            'sqlite' => 'whereDate',
            default => 'where',
        };
    }

    protected function getFilterName(): string
    {
        $postfix = $this->getPostFix() ?? config('pipeline-query-collection.date_from_postfix');

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
