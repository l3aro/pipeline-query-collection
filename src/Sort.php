<?php

namespace Baro\PipelineQueryCollection;

class Sort
{
    public function handle($query, \Closure $next)
    {
        $sort = request()->input('sort', []);
        if (!is_array($sort)) {
            $sort = [$sort];
        }
        foreach ($sort as $field => $direction) {
            $query->orderBy($field, $direction);
        }
        return $next($query);
    }
}