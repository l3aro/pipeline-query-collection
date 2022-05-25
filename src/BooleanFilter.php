<?php

namespace Baro\PipelineQueryCollection;

class BooleanFilter extends BaseFilter
{
    public function __construct($field)
    {
        parent::__construct();
        $this->field = $field;
    }

    public function handle($query, \Closure $next)
    {
        $filterName = "{$this->detector}.{$this->field}";
        if ($this->shouldFilter($filterName)) {
            $query->where($this->field, request()->input($filterName) ? 1 : 0);
        }

        return $next($query);
    }
}
