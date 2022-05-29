<?php

namespace Baro\PipelineQueryCollection;

class RelationFilter extends BaseFilter
{
    private $relation;

    public function __construct($relation, $field)
    {
        parent::__construct();
        $this->relation = $relation;
        $this->field = $field;
    }

    public function handle($query, \Closure $next)
    {
        $filterName = "{$this->detector}{$this->relation}_{$this->field}";
        $toSearch = request()->input($filterName);
        $action = is_array($toSearch) ? 'whereIn' : 'where';
        $query->whereHas($this->relation, function ($query) use ($action, $toSearch) {
            $query->{$action}($this->getSearchColumn(), $toSearch);
        });

        return $next($query);
    }
}
