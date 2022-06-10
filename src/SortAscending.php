<?php

namespace Baro\PipelineQueryCollection;

class SortAscending extends BaseSort
{
    public function __construct()
    {
        parent::__construct();
    }

    public function handle($query, \Closure $next)
    {
        $sort = $this->request->input('sort', []);
        if (!is_array($sort)) {
            $sort = [$sort];
        }
        foreach ($sort as $field) {
            $query->orderBy($field, 'asc');
        }

        return $next($query);
    }
}
