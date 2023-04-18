<?php

declare(strict_types=1);

namespace Majhar\CommissionCalculation\Services;

use Majhar\CommissionCalculation\Interfaces\ShouldCacheable;
use Majhar\CommissionCalculation\Traits\MakeableTrait;

class CacheGlobals implements ShouldCacheable
{
    use MakeableTrait;

    /**
     * Undocumented function.
     *
     * @param string $key
     *
     * @return void
     */
    public function purge($key)
    {
        unset($GLOBALS[$key]);
    }

    /**
     * Undocumented function.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function put($key, $value)
    {
        $GLOBALS[$key] = $value;

        return $this;
    }

    /**
     * Undocumented function.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($GLOBALS[$key]);
    }

    /**
     * Undocumented function.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $GLOBALS[$key];
    }
}
