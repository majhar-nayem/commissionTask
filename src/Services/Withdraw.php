<?php

declare(strict_types=1);

namespace Majhar\CommissionCalculation\Services;

use DateTime;
use Majhar\CommissionCalculation\Services\CacheGlobals as Cache;
use Majhar\CommissionCalculation\Traits\ExchangeSetterTrait;
use Majhar\CommissionCalculation\Transformers\Collection;


class Withdraw
{
    use ExchangeSetterTrait;

    const PRIVATE_COMMISSION_FEE   = '0.3';
    const BUSINESS_COMMISSION_FEE    = '0.5';
    const FREE_PER_WEEK    = '1000';
    const FREE_WITHDRAW_COUNT = 3;
    private CacheGlobals $cache;
    private Collection $collection;

    /**
     * Undocumented function.
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
        $this->cache      = Cache::make();
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    public function fee()
    {
        $type = $this->collection->userType();

        return Math::add(0, $this->{'feeFor'.ucfirst($type)}());
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    protected function feeForPrivate()
    {
        $amount = $this->analyzePrivateAmount();

        return Math::mul(
            $amount,
            Math::div(static::PRIVATE_COMMISSION_FEE, 100),
        );
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    protected function feeForBusiness()
    {

        return Math::mul(
            $this->collection->amount(),
            Math::div('0.5', 100),
        );
    }

    /**
     * Undocumented function.
     *
     * @return string|float
     */
    protected function analyzePrivateAmount()
    {
        $this->incrementWithdrawAttempt();

        $allocated = $this->getUserAllocatedFreeWeek();

        $operationAmount = $this->exchange->convert(
            $this->collection->currency(),
            $this->collection->amount()
        );

        // we need to know the allocated + the collection's amount
        $basis = Math::add($allocated, $operationAmount);
        if (
            $this->isWithdrawFreeWeek($basis)
            && $this->stillInMinimumWithdraw()
        ) {
            $this->updateUserAllocatedFreeWeek(
                Math::add($allocated, $operationAmount)
            );

            return '0.00';
        }

        // checking if our basis is greater than the quota, if so we need to pre-calculate the value that
        // we need to deduct from the remaining quota it has
        $remaining = Math::sub(
            static::FREE_PER_WEEK,
            $allocated
        );

        $this->updateUserAllocatedFreeWeek(
            Math::add($allocated, $remaining)
        );

        $amount = abs((float)Math::sub($operationAmount, $remaining));

        return $this->exchange->convertBack(
            $this->collection->currency(),
            $amount
        );
    }

    /**
     * Undocumented function.
     *
     * @return string|float
     */
    protected function getUserAllocatedFreeWeek()
    {
        $key = sprintf('%s-allocated', $this->generateTagKey());

        if (!$this->cache->has($key)) {
            $this->cache->put($key, 0);
        }

        return $this->cache->get($key);
    }

    /**
     * Undocumented function.
     *
     * @param Collection $collection
     * @param mixed      $value
     *
     * @return bool
     */
    protected function updateUserAllocatedFreeWeek($value)
    {
        $key = sprintf('%s-allocated', $this->generateTagKey());

        $this->cache->put($key, $value);

        return true;
    }

    /**
     * Undocumented function.
     *
     * @return string|float
     */
    protected function getWithdrawAttempts()
    {
        $key = sprintf('%s-withdraw-attempts', $this->generateTagKey());

        return $this->cache->get($key);
    }

    /**
     * Undocumented function.
     *
     * @return bool
     */
    protected function incrementWithdrawAttempt()
    {
        $key = sprintf(
            '%s-withdraw-attempts',
            $this->generateTagKey($this->collection)
        );

        if ($this->cache->has($key)) {
            $this->cache->put(
                $key,
                Math::add($this->cache->get($key), 1, 0)
            );
        } else {
            $this->cache->put($key, 1);
        }

        return true;
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    protected function generateTagKey()
    {
        list($year, $month, $week) = $this->getYearAndWeek();

        // this is where we determine if the month is december
        // yet the week number is 1, meaning that week
        // is already the last week combining the next year first week.
        if ($month === 12 && $week === 1) {
            ++$year;
        }

        $this->collection->setValue('interpreted_year', $year);
        $this->collection->setValue('interpreted_week', $week);

        return strtr('{year}-{week}-{user}', [
            '{year}' => $year,
            '{week}' => $week,
            '{user}' => $this->collection->userId(),
        ]);
    }

    /**
     * Undocumented function.
     *
     * @return array
     */
    protected function getYearAndWeek()
    {
        $date = new DateTime($this->collection->date());

        return [
            (int) $date->format('Y'),
            (int) $date->format('m'),
            (int) $date->format('W'),
        ];
    }

    /**
     * Undocumented function.
     *
     * @return bool
     */
    protected function stillInMinimumWithdraw()
    {
        if ($this->getWithdrawAttempts() <= static::FREE_WITHDRAW_COUNT) {
            return true;
        }

        return false;
    }

    /**
     * Determine if still free commission fee.
     *
     * @param string|float $basis
     *
     * @return bool
     */
    protected function isWithdrawFreeWeek($basis)
    {

        if (
            $basis >= static::FREE_PER_WEEK &&
            $basis <= static::FREE_PER_WEEK
        ) {
            return true;
        }

        // if basis is still lower than the natural
        // free per week
        if ($basis < static::FREE_PER_WEEK) {
            return true;
        }

        return false;
    }
}
