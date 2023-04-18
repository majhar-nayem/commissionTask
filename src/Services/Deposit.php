<?php

declare(strict_types=1);

namespace Majhar\CommissionCalculation\Services;

use Majhar\CommissionCalculation\Traits\ExchangeSetterTrait;
use Majhar\CommissionCalculation\Transformers\Collection;


class Deposit
{
    use ExchangeSetterTrait;

    const COMMISSION_FEE = '0.03';

    /**
     * Undocumented variable.
     *
     * @var \Majhar\CommissionCalculation\Transformers\Collection
     */
    private $collection;

    /**
     * Undocumented function.
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    public function fee()
    {
        $amount = $this->exchange->convert(
            $this->collection->currency(),
            $this->collection->amount()
        );

        $fee = Math::mul(
            $amount,
            Math::div(static::COMMISSION_FEE, 100)
        );

        return $fee;
    }
}
