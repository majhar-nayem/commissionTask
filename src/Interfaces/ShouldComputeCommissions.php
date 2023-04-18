<?php

declare(strict_types=1);

namespace Majhar\CommissionCalculation\Interfaces;

use Majhar\CommissionCalculation\Transformers\Collection;


interface ShouldComputeCommissions
{
    /**
     * Undocumented function.
     *
     * @return string|float
     */
    public function compute(Collection $collection);
}
