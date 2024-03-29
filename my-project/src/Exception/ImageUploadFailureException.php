<?php

namespace App\Exception;

class ImageUploadFailureException extends \Exception
{
    public function __construct(string $message = 'Image upload fail.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
