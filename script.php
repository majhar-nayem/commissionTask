<?php

use Majhar\CommissionCalculation\Application;
use Majhar\CommissionCalculation\Helpers\Parsers\Csv;
use Majhar\CommissionCalculation\Services\Commission;
use Majhar\CommissionCalculation\Services\CurrencyExchange;
use Majhar\CommissionCalculation\Services\Deposit;
use Majhar\CommissionCalculation\Services\Withdraw;

require __DIR__ . '/bootstrap.php';

$commission = new Commission;
$commission->setOperators([
    'deposit'  => Deposit::class,
    'withdraw' => Withdraw::class,
]);
$commission->setCurrencyExchange(new CurrencyExchange());

$collections = Application::make() // or: Manager::getInstance()
    ->setCommission($commission)
    ->setData(new Csv(__DIR__ . '/tests/input1.csv'))
    ->handle();

require __DIR__ . '/printer.php';
