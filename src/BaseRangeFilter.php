<?php

namespace Modules\Core\Filters;

use Baro\PipelineQueryCollection\BaseFilter;

abstract class BaseRangeFilter extends BaseFilter
{
    protected ?string $postfix = null;
    protected string $operator;

    public function __construct($field)
    {
        parent::__construct();

        $this->field = $field;
    }

    abstract protected function getDefaultPostfix(): string;

    protected function apply(): static
    {
        $searchValue = $this->getSearchValue();

        $this->query->where($this->field, $this->operator, $searchValue);

        return $this;
    }

    protected function getFilterName(): string
    {
        $postfix = $this->getPostfix() ?? $this->getDefaultPostfix();

        return "{$this->detector}{$this->field}_{$postfix}";
    }

    public function setPostfix(string $postfix): self
    {
        $this->postfix = $postfix;

        return $this;
    }

    private function getPostfix(): ?string
    {
        return $this->postfix;
    }
}
