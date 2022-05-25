<?php

namespace Baro\PipelineQueryCollection;

class SortAscending
{
    public function handle($query, \Closure $next)
    {
        $sort = request()->input('sort', []);
        if (!is_array($sort)) {
            $sort = [$sort];
        }
        foreach ($sort as $field) {
            $query->orderBy($field, 'asc');
        }
        return $next($query);
    }
}
