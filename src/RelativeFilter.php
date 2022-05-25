<?php

namespace Baro\PipelineQueryCollection;

use Baro\PipelineQueryCollection\Enums\RelativeWildcardPositionEnum;
use Closure;

class RelativeFilter extends BaseFilter
{
    private $wildcardPosition;

    public function __construct($field, RelativeWildcardPositionEnum|string $wildcardPosition = null)
    {
        $this->filterOn($field);
        if (is_null($wildcardPosition)) {
            $wildcardPosition = config('pipeline-query-collection.relative_wildcard_position');
        }
        if (!$wildcardPosition instanceof RelativeWildcardPositionEnum) {
            $wildcardPosition = RelativeWildcardPositionEnum::from($wildcardPosition);
        }
        $this->wildcardPosition = $wildcardPosition;
    }

    public function handle($query, Closure $next)
    {
        $filterName = "{$this->detector}.{$this->field}";
        $toSearch = match ($this->wildcardPosition) {
            RelativeWildcardPositionEnum::BOTH => "%{$this->field}%",
            RelativeWildcardPositionEnum::RIGHT => "{$this->field}%",
            RelativeWildcardPositionEnum::LEFT => "%{$this->field}",
        };
        if ($this->shouldFilter($filterName)) {
            $query->where($this->field, 'like', $toSearch);
        }
        return $next($query);
    }
}
