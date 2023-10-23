<?php

namespace Baro\PipelineQueryCollection;

abstract class BaseSort extends BasePipe
{
    protected array $sort;

    protected mixed $sortValue = null;

    public function value(mixed $sortValue): static
    {
        $this->sortValue = $sortValue;

        return $this;
    }

    public function handle($query, \Closure $next)
    {
        $this->query = $query;
        if (!is_null($this->sortValue)) {
            $sort = $this->sortValue;
        } else {
            $sort = $this->request->input('sort', []);
        }
        $this->sort = !is_array($sort) ? [$sort] : $sort;
        $this->apply();

        return $next($this->query);
    }
}
