<?php

namespace Majhar\CommissionCalculation\Tests\Parsers;

use Majhar\CommissionCalculation\Helpers\Parsers\Arrayable;
use Majhar\CommissionCalculation\Transformers\Collection;
use PHPUnit\Framework\TestCase;

class ArrayableTest extends TestCase
{
    public function testArrayableCollection()
    {
        $arr = new Arrayable($lists = [
            ['2014-12-31','4','private','withdraw','1200.00','EUR'],
            ['2015-01-01','4','private','withdraw','1000.00','EUR'],
            ['2016-01-05','4','private','withdraw','1000.00','EUR'],
            ['2016-01-05','1','private','deposit','200.00','EUR'],
            ['2016-01-06','2','business','withdraw','300.00','EUR'],
            ['2016-01-07','1','private','withdraw','1000.00','EUR'],
            ['2016-01-10','1','private','withdraw','100.00','EUR'],
            ['2016-01-10','2','business','deposit','1000000.00','EUR'],
            ['2016-01-10','3','private','withdraw','1000.00','EUR'],
            ['2016-02-15','1','private','withdraw','300.00','EUR'],
            ['2016-01-07','1','private','withdraw','100.00','USD'],
            ['2016-02-19','5','private','withdraw','3000000','JPY'],
        ]);

        $collections = $arr->collections();

        $idx = array_rand($lists);
        $list = $lists[$idx];

        $this->assertInstanceOf(Collection::class, $collections[$idx]);
        $this->assertEquals($collections[$idx]->date(), $list[0]);
        $this->assertEquals($collections[$idx]->userId(), $list[1]);
        $this->assertEquals($collections[$idx]->userType(), $list[2]);
        $this->assertEquals($collections[$idx]->operationType(), $list[3]);
        $this->assertEquals($collections[$idx]->amount(), $list[4]);
        $this->assertEquals($collections[$idx]->currency(), $list[5]);
    }
}
