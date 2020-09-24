<?php

declare(strict_types=1);

namespace Enterprise;

class NonexistentConfigurationFileException extends \Exception
{
    public function __construct(string $message = 'Configuration file not found: ' . CONFIGURATION_FILENAME, int $code = 0, \Exception $previousException = null)
    {
        parent::__construct($message, $code, $previousException);
    }
}
