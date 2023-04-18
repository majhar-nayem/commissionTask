<?php

namespace Majhar\CommissionCalculation\Tests\Services;

use Majhar\CommissionCalculation\Services\CurrencyExchange;
use Majhar\CommissionCalculation\Services\Math;
use Majhar\CommissionCalculation\Services\Withdraw;
use Majhar\CommissionCalculation\Transformers\Collection;
use PHPUnit\Framework\TestCase;

class WithdrawTest extends TestCase
{
    private function createInstance($datum)
    {
        $instance = new Withdraw(new Collection($datum));

        $instance->setCurrencyExchange(new CurrencyExchange());

        return $instance;
    }

    public function testBusinessWithdraw()
    {
        $instance = $this->createInstance([
            '2019-01-01',
            4,
            'business',
            'Withdraw',
            5000.00,
            'EUR',
        ]);

        // amount * (% commission fee / 100%)
        // 5000 * (0.5 / 100)
        // 5000 * 0.005
        // should be 25
        $this->assertEquals(Math::roundUp($instance->fee()), '25.00');
    }

    public function testPrivateUserWithdraw()
    {
        $userId = 999;
        $records = [
            [
                'collection' => ['2019-12-02', $userId, 'private', 'Withdraw', 300, 'EUR'],
                'result'     => '0.00',
            ],
            [
                'collection' => ['2019-12-02', $userId, 'private', 'Withdraw', 300, 'EUR'],
                'result'     => '0.00',
            ],
            [
                'collection' => ['2019-12-03', $userId, 'private', 'Withdraw', 300, 'EUR'],
                'result'     => '0.00',
            ],
            [
                // 1000 allocated free of the week
                'collection' => ['2019-12-08', $userId, 'private', 'Withdraw', 100, 'EUR'],
                'result'     => '0.00',
            ],
            [
                'collection' => ['2019-12-08', $userId, 'private', 'Withdraw', 100, 'EUR'],
                'result'     => '0.30',
            ],
            [
                'collection' => ['2019-12-08', $userId, 'private', 'Withdraw', 300, 'EUR'],
                'result'     => '0.90',
            ],

            // this is Monday, different week
            // the user cashed out for 1100
            // so the result must be "0.30" commission fee only
            [
                'collection' => ['2019-12-09', $userId, 'private', 'Withdraw', 1100, 'EUR'],
                'result'     => '0.30',
            ],
        ];

        foreach ($records as $record) {
            $instance = $this->createInstance($record['collection']);

            $this->assertEquals(Math::roundUp($instance->fee()), $record['result']);
        }
    }
}
