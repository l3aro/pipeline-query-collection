<?php

namespace Baro\PipelineQueryCollection;

class SortDescending
{
    public function handle($query, \Closure $next)
    {
        $sort = request()->input('sort', []);
        if (! is_array($sort)) {
            $sort = [$sort];
        }
        foreach ($sort as $field) {
            $query->orderBy($field, 'desc');
        }

        return $next($query);
    }
}
