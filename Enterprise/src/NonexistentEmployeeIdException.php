<?php

declare(strict_types=1);

namespace Enterprise;

class NonexistentEmployeeIdException extends \Exception
{
    public function __construct(string $message = 'The specified employee identification number does not exist.', int $code = 0, \Exception $previousException = null)
    {
        parent::__construct($message, $code, $previousException);
    }
}
