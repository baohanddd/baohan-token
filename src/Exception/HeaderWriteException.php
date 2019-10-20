<?php
namespace baohan\token\Exception;

use Throwable;

class HeaderWriteException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        if (!$message) $message = "Fails to write data into header...";
        if (!$code)    $code = 500;
        parent::__construct($message, $code, $previous);
    }
}