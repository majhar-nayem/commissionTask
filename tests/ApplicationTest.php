<?php

namespace Majhar\CommissionCalculation\Tests;

use Majhar\CommissionCalculation\Application;
use Majhar\CommissionCalculation\Helpers\Parsers\Csv;
use Majhar\CommissionCalculation\Transformers\Collection;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testBootstrap()
    {
        $collections = Application::make()
            ->setData(new Csv(__DIR__ . '/input1.csv'))
            ->handle();

        $this->assertTrue(is_array($collections));
        $this->assertInstanceOf(Collection::class, $collections[0]);
    }
}
