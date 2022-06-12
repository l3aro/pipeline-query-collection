<?php

namespace Baro\PipelineQueryCollection;

abstract class BaseSort extends BasePipe
{
    protected array $sort;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle($query, \Closure $next)
    {
        $this->query = $query;
        $sort = $this->request->input('sort', []);
        $this->sort = !is_array($sort) ? [$sort] : $sort;
        $this->apply();
        return $next($this->query);
    }
}
