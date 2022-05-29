<?php

namespace Baro\PipelineQueryCollection;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseFilter
{
    protected string $ignore;
    protected string $field;
    protected string $detector;
    protected ?string $searchColumn = null;

    public function __construct()
    {
        $this->detector = config('pipeline-query-collection.detect_key');
    }

    abstract protected function apply(Builder $query): Builder;

    public function handle($query, \Closure $next)
    {
        if (!$this->shouldFilter($this->getFilterName())) {
            return $next($query);
        }

        return $next($this->apply($query));
    }

    protected function getFilterName(): string
    {
        return "{$this->detector}{$this->field}";
    }

    protected function getSearchValue(): array
    {
        $searchValue =  request()->input($this->getFilterName());
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
