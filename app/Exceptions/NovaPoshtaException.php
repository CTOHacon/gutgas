<?php

namespace App\Exceptions;

use RuntimeException;

class NovaPoshtaException extends RuntimeException
{
    /**
     * HTTP status the API endpoints should answer with.
     */
    public function __construct(string $message, protected int $status = 502)
    {
        parent::__construct($message);
    }

    public function status(): int
    {
        return $this->status;
    }
}
