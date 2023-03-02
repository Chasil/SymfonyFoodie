<?php

namespace App\Exception;

class ImageCreatorFailure extends \Exception
{
    public function __construct(string $message = 'Image has not been created.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
