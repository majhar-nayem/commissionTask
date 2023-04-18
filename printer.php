<?php

$table = new LucidFrame\Console\ConsoleTable();

foreach ($collections as $collection) {
    $table->addRow()
        ->addColumn($collection->getValue('roundUpFee'));
}

$table->display();

echo sprintf("> Speed: %s\n", microtime(true) - TEST_START);
