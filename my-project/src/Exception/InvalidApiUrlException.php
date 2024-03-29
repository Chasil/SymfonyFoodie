<?php

namespace App\Exception;

class InvalidApiUrlException extends \Exception
{
    public function __construct(string $message = 'Invalid API URL.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
