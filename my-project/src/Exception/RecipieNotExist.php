<?php

namespace App\Exception;

class RecipieNotExist extends \Exception
{
    public function __construct(string $message = "Recipie does not exist.", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}