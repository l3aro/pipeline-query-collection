<?php

namespace Baro\PipelineQueryCollection;

final class BitwiseFilter extends BaseFilter
{
    public function __construct($field)
    {
        parent::__construct();
        $this->field = $field;
    }

    public static function make($field): static
    {
        return new self($field);
    }

    protected function apply(): static
    {
        $flag = null;
        foreach ($this->getSearchValue() as $value) {
            $flag ??= intval($value);
            $flag = intval($flag) | intval($value);
        }
        if ($flag === null) {
            return $this;
        }
        $this->query->whereRaw("{$this->getSearchColumn()} & ? = ?", [$flag, $flag]);

        return $this;
    }
}
