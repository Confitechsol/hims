<?php

namespace App\Exceptions;

use Exception;

class IpdConstraintException extends Exception
{
    public function __construct(
        public readonly string $codeKey,
        string $message,
        public readonly array $details = [],
        int $httpStatus = 422
    ) {
        parent::__construct($message, $httpStatus);
    }

    public function toArray(): array
    {
        return [
            'ok' => false,
            'code' => $this->codeKey,
            'message' => $this->getMessage(),
            'details' => $this->details,
        ];
    }
}
