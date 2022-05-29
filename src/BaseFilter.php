<?php

namespace Baro\PipelineQueryCollection;

abstract class BaseFilter
{
    protected string $ignore;
    protected string $field;
    protected string $detector;
    protected string $searchColumn = null;

    public function __construct()
    {
        $this->detector = config('pipeline-query-collection.detect_key');
    }

    abstract public function handle($query, \Closure $next);

    public function filterOn(string $searchColumn)
    {
        $this->searchColumn = $searchColumn;

        return $this;
    }

    protected function getSearchColumn()
    {
        return $this->searchColumn ?? $this->field;
    }

    public function ignore(string $ignore = '')
    {
        $this->ignore = $ignore;

        return $this;
    }

    public function detectBy(string $detector)
    {
        $this->detector = $detector;

        return $this;
    }

    protected function shouldFilter(string $key)
    {
        if (!request()->has($key)) {
            return false;
        }

        if (isset($this->ignore) && $this->ignore === request()->input($key)) {
            return false;
        }

        return true;
    }
}
