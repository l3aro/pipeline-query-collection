<?php

namespace Baro\PipelineQueryCollection;

abstract class BaseFilter extends BasePipe
{
    protected mixed $ignore;
    protected ?string $field = null;
    protected string $detector;
    protected ?string $searchColumn = null;

    public function __construct()
    {
        parent::__construct();
        $this->ignore(null);
        $this->detector = config('pipeline-query-collection.detect_key');
    }

    public function handle($query, \Closure $next)
    {
        $this->query = $query;
        if (!$this->shouldFilter($this->getFilterName())) {
            return $next($query);
        }
        $this->apply();
        return $next($this->query);
    }

    protected function getFilterName(): string
    {
        return "{$this->detector}{$this->field}";
    }

    protected function getSearchValue(): array
    {
        $searchValue =  $this->request->input($this->getFilterName());
        if (!is_array($searchValue)) {
            $searchValue = [$searchValue];
        }
        return $searchValue;
    }

    public function filterOn(string $searchColumn)
    {
        $this->searchColumn = $searchColumn;

        return $this;
    }

    protected function getSearchColumn()
    {
        return $this->searchColumn ?? $this->field;
    }

    public function ignore(mixed $ignore = '')
    {
        $this->ignore = $ignore;

        return $this;
    }

    public function field(mixed $field = '')
    {
        $this->field = $field;

        return $this;
    }

    public function detectBy(string $detector)
    {
        $this->detector = $detector;

        return $this;
    }

    protected function shouldFilter(string $key)
    {
        if (!$this->request->has($key)) {
            return false;
        }

        if ($this->ignore === $this->request->input($key)) {
            return false;
        }

        return true;
    }
}
