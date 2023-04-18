<?php

declare(strict_types=1);

namespace Majhar\CommissionCalculation\Traits;

use Majhar\CommissionCalculation\Interfaces\ShouldConvertCurrencies;

trait ExchangeSetterTrait
{
    /**
     * Undocumented variable.
     *
     * @var \Majhar\CommissionCalculation\Interfaces\ShouldConvertCurrencies
     */
    protected $exchange;

    /**
     * Undocumented function.
     *
     * @return self
     */
    public function setCurrencyExchange(ShouldConvertCurrencies $exchange)
    {
        $this->exchange = $exchange;

        return $this;
    }
}
