<?php

declare(strict_types=1);

namespace Majhar\CommissionCalculation\Exceptions;


class WrongFileExtension extends \Exception
{
    /**
     * Undocumented function.
     *
     * @param string $message
     * @param string $ext
     */
    public function __construct(string $message = null, int $code = 0, string $ext = null)
    {
        if ($message === null) {
            if ($ext === null) {
                $message = 'Wrong file extension.';
            } else {
                $message = "Expected extension must be [{$ext}] format";
            }
        }

        parent::__construct($message, $code);
    }
}
