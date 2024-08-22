<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

class NotFoundException extends Exception implements Throwable
{
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Not found.', $code, $previous);
    }
}
