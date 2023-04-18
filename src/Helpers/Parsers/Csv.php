<?php

declare(strict_types=1);

namespace Majhar\CommissionCalculation\Helpers\Parsers;

use Majhar\CommissionCalculation\Exceptions\WrongFileExtension;
use Majhar\CommissionCalculation\Helpers\Filesystem\Local as Filesystem;
use Majhar\CommissionCalculation\Interfaces\ShouldProvideCollection;
use Majhar\CommissionCalculation\Transformers\Collection;


class Csv extends Filesystem implements ShouldProvideCollection
{
    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $collections = [];

    /**
     * Undocumented function.
     *
     * @param string $path path of your csv file
     */
    public function __construct($path)
    {
        if (!$this->isCsv($path)) {
            throw new WrongFileExtension(null, 0, '.csv');
        }

        $this->setPath($path);
    }

    /**
     * {@inheritdoc}
     */
    public function collections(): array
    {
        if (empty($this->collections)) {
            foreach ($this->getRecords() as $record) {
                $this->collections[] = new Collection($record);
            }
        }

        return $this->collections;
    }

    /**
     * Undocumented function.
     *
     * @return array
     */
    protected function getRecords()
    {
        $data = [];
        $fp   = fopen($this->getPath(), 'rb');

        while (!feof($fp)) {
            if (is_array($appendable = fgetcsv($fp))) {
                $data[] = $appendable;
            }
        }

        fclose($fp);

        return $data;
    }

    /**
     * Undocumented function.
     *
     * @param string $path
     *
     * @return bool
     */
    private function isCsv($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        if ($ext === 'csv') {
            return true;
        }

        return false;
    }
}
