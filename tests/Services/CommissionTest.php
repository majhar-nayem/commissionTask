<?php

namespace Majhar\CommissionCalculation\Tests\Services;

use Majhar\CommissionCalculation\Services\Commission;
use Majhar\CommissionCalculation\Services\CurrencyExchange;
use Majhar\CommissionCalculation\Services\Deposit;
use Majhar\CommissionCalculation\Services\Math;
use Majhar\CommissionCalculation\Services\Withdraw;
use Majhar\CommissionCalculation\Transformers\Collection;
use PHPUnit\Framework\TestCase;

class CommissionTest extends TestCase
{
    public function testPrivateDeposit200Euro()
    {
        $commission = new Commission();
        $commission->setCurrencyExchange(new CurrencyExchange());

        $amount = $commission->compute(new Collection([
            '2016-01-05',
            $userId = 1,
            'private',
            'deposit',
            200.00,
            'EUR'
        ]));
        $this->assertEquals(Math::roundUp($amount), '0.06');
    }

    public function testBusinessWithdraw300Euro()
    {
        $commission = new Commission();
        $commission->setCurrencyExchange(new CurrencyExchange());

        $amount = $commission->compute(new Collection([
            '2016-01-06',
            $userId = 2,
            'business',
            'withdraw',
            300.00,
            'EUR'
        ]));
        $this->assertEquals(Math::roundUp($amount), '1.50');
    }


    public function testScenario()
    {
        $commission = new Commission();
        $commission->setCurrencyExchange(new CurrencyExchange());

        $amount = $commission->compute(new Collection([
            '2019-12-09',
            $userId = 1000,
            'business',
            'deposit',
            500,
            'EUR'
        ]));
        $this->assertEquals(Math::roundUp($amount), '0.15');

        $amount = $commission->compute(new Collection([
            '2019-12-09',
            $userId = 1000,
            'business',
            'deposit',
            300,
            'EUR'
        ]));
        $this->assertEquals(Math::roundUp($amount), '0.09');
    }

    public function testSetOperators()
    {
        $commission = new Commission();
        $commission->setCurrencyExchange(new CurrencyExchange());
        $commission->setOperators([
            'deposit' => Deposit::class,
            'withdraw' => Withdraw::class,
        ]);

        $amount = $commission->compute(new Collection([
            '2019-12-09',
            $userId = 1000,
            'private',
            'withdraw',
            900,
            'EUR'
        ]));

        $this->assertEquals(Math::roundUp($amount), '0.00');
    }
}
