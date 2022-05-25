<?php

namespace Baro\PipelineQueryCollection;

use Closure;

class ScopeFilter extends BaseFilter
{
    public function __construct($scopeName)
    {
        parent::__construct();
        $this->filterOn($scopeName);
    }

    public function handle($query, Closure $next)
    {
        $filterName = "{$this->detector}{$this->field}";
        if ($this->shouldFilter($filterName)) {
            $query->{$this->field}(request()->input($filterName));
        }

        return $next($query);
    }
}
