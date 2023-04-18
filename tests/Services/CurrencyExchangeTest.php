<?php

namespace Majhar\CommissionCalculation\Tests\Services;

use Majhar\CommissionCalculation\Services\CurrencyExchange;
use PHPUnit\Framework\TestCase;
use Majhar\CommissionCalculation\Services\Math;

class CurrencyExchangeTest extends TestCase
{
    public function setUp() : void
    {
        $this->exchange = new CurrencyExchange();
    }

    public function testScenario()
    {
        $this->assertEquals(Math::roundUp($this->exchange->convert('EUR', 1)), 1);
        $this->assertEquals(Math::roundUp($this->exchange->convert('JPY', 129.53)), 1);
    }

    public function testUnsupportedCurrency()
    {
        try {
            $this->assertTrue((bool)$this->exchange->convert('PHP', 100));
        } catch (\Throwable $e) {
            $this->assertEquals(
                $e->getMessage(),
                'We currently don\'t support [PHP] this type of currency.'
            );
        }
    }
}
