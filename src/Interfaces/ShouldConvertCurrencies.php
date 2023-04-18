<?php

declare(strict_types=1);

namespace Majhar\CommissionCalculation\Interfaces;


interface ShouldConvertCurrencies
{
    /**
     * Undocumented function.
     *
     * @param string    $currency
     * @param int|float $value
     *
     * @return string|float
     */
    public function convert($currency, $value);
}
