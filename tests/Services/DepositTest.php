<?php

namespace Majhar\CommissionCalculation\Tests\Services;

use Majhar\CommissionCalculation\Services\CurrencyExchange;
use Majhar\CommissionCalculation\Services\Deposit;
use Majhar\CommissionCalculation\Services\Math;
use Majhar\CommissionCalculation\Transformers\Collection;
use PHPUnit\Framework\TestCase;

class DepositTest extends TestCase
{
    public function testDepositFee()
    {
        $instance = new Deposit(new Collection([
            '2019-01-01',
            4,
            'private',
            'deposit',
            1200.00,
            'EUR',
        ]));
        $instance->setCurrencyExchange(new CurrencyExchange());

        $this->assertEquals(Math::roundUp($instance->fee()), '0.36');
    }
}
