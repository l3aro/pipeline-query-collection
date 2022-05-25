<?php

namespace Baro\PipelineQueryCollection;

use Baro\PipelineQueryCollection\Enums\MotionEnum;

class DateToFilter extends BaseFilter
{
    private $motion;

    public function __construct($field = 'created_at', MotionEnum|string $motion = MotionEnum::FIND)
    {
        parent::__construct();
        $this->field = $field;
        if (!$motion instanceof MotionEnum) {
            $motion = MotionEnum::from($motion);
        }
        $this->motion = $motion;
    }

    public function handle($query, \Closure $next)
    {
        $postfix = config('pipeline-query-collection.date_to_postfix');
        $filterName = "{$this->detector}.{$this->field}{$postfix}";
        if ($this->shouldFilter($filterName)) {
            $operator = $this->motion === MotionEnum::FIND ? '<=' : '<';
            $query->where($this->field, $operator, request()->input($filterName));
        }
        return $next($query);
    }
}
