<?php

declare(strict_types=1);

namespace Majhar\CommissionCalculation\Helpers\Parsers;

use Majhar\CommissionCalculation\Interfaces\ShouldProvideCollection;
use Majhar\CommissionCalculation\Transformers\Collection;

class Arrayable implements ShouldProvideCollection
{
    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $collections = [];

    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Undocumented function.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function collections(): array
    {
        if (empty($this->collections)) {
            foreach ($this->data as $datum) {
                $this->collections[] = new Collection($datum);
            }
        }

        return $this->collections;
    }
}
