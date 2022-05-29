<?php

namespace Baro\PipelineQueryCollection;

use Closure;

class ExactFilter extends BaseFilter
{
    public function __construct($field)
    {
        parent::__construct();
        $this->filterOn($field);
    }

    public function handle($query, Closure $next)
    {
        $filterName = "{$this->detector}{$this->field}";
        if ($this->shouldFilter($filterName)) {
            $query->where($this->getSearchColumn(), request()->input($filterName));
        }

        return $next($query);
    }
}
