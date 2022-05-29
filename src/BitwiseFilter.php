<?php

namespace Baro\PipelineQueryCollection;

use Illuminate\Database\Eloquent\Builder;

class BitwiseFilter extends BaseFilter
{
    public function __construct($field)
    {
        parent::__construct();
        $this->field = $field;
    }

    protected function apply(Builder $query): Builder
    {
        foreach ($this->getSearchValue() as $value) {
            $query->whereRaw("{$this->getSearchColumn()} & ? = ?", [$value, $value]);
        }

        return $query;
    }
}
