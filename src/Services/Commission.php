<?php

declare(strict_types=1);

namespace Majhar\CommissionCalculation\Services;

use Majhar\CommissionCalculation\Interfaces\ShouldComputeCommissions;
use Majhar\CommissionCalculation\Traits\ExchangeSetterTrait;
use Majhar\CommissionCalculation\Transformers\Collection;

class Commission implements ShouldComputeCommissions
{
    use ExchangeSetterTrait;

    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $operators = [
        'deposit' => Deposit::class,
        'withdraw' => Withdraw::class,
    ];

    /**
     * Undocumented function.
     *
     * @return self
     */
    public function setOperators(array $operators = [])
    {
        $this->operators = $operators;

        return $this;
    }

    /**
     * Undocumented function.
     *
     * @return string|float
     */
    public function compute(Collection $collection)
    {
        $class = $this->operators[$collection->operationType()];
        $operator = new $class($collection);
        $operator->setCurrencyExchange($this->exchange);
        $amount = $operator->fee();

        $collection->setValue('rawFee', $amount);
        $collection->setValue('roundUpFee', Math::roundUp($amount, $collection->currency()));
        $collection->setValue('convertedFee', Math::roundUp($this->exchange->convert(
            $collection->currency(),
            $amount
        )));

        return $amount;
    }
}
