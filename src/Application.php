<?php

declare(strict_types=1);

namespace Majhar\CommissionCalculation;

use Majhar\CommissionCalculation\Interfaces\ShouldComputeCommissions;
use Majhar\CommissionCalculation\Interfaces\ShouldProvideCollection;
use Majhar\CommissionCalculation\Services\Commission;
use Majhar\CommissionCalculation\Services\CurrencyExchange;
use Majhar\CommissionCalculation\Traits\MakeableTrait;

/**
 * This is the application or other developers call it as a Manager
 * where it bootstraps the services.
 */
class Application
{
    use MakeableTrait;

    /**
     * Undocumented variable.
     *
     * @var \Majhar\CommissionCalculation\Interfaces\ShouldComputeCommissions
     */
    protected $commission;

    /**
     * Undocumented variable.
     *
     * @var \Majhar\CommissionCalculation\Interfaces\ShouldProvideCollection
     */
    protected $collector;

    /**
     * Undocumented function.
     */
    public function __construct()
    {
        $exchange   = new CurrencyExchange();
        $commission = new Commission();
        $commission->setCurrencyExchange($exchange);

        $this->setCommission($commission);
    }

    /**
     * Undocumented function.
     *
     * @return self
     */
    public function setCommission(ShouldComputeCommissions $commission)
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * Undocumented function.
     *
     * @return self
     */
    public function setData(ShouldProvideCollection $collector)
    {
        $this->collector = $collector;

        return $this;
    }

    /**
     * Undocumented function.
     *
     * @return array
     */
    public function handle()
    {
        foreach ($this->collector->collections() as $collection) {
            $this->commission->compute($collection);
        }

        return $this->collector->collections();
    }
}
