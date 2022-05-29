<?php

namespace Baro\PipelineQueryCollection;

class BitwiseFilter extends BaseFilter
{
    public function __construct($field)
    {
        parent::__construct();
        $this->field = $field;
    }

    public function handle($query, \Closure $next)
    {
        $filterName = "{$this->detector}{$this->field}";
        $toSearch = request()->input($filterName);
        if (! $this->shouldFilter($filterName)) {
            return $next($query);
        }

        if (! is_array($toSearch)) {
            $toSearch = [$toSearch];
        }

        foreach ($toSearch as $search) {
            $query->where($this->getSearchColumn(), '&', $search);
        }

        return $next($query);
    }
}
