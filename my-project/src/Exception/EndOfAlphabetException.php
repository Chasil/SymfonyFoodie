<?php

namespace App\Exception;

class EndOfAlphabetException extends \Exception implements GetApiRecipiesException
{
    public function __construct(string $message = 'End of Alphabet reached.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
