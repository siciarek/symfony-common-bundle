<?php

namespace Siciarek\SymfonyCommonBundle\Services\Net\Exceptions;

class InvalidUrl extends \Exception
{
    public function __construct($message = 'Invalid url.', $code = 404, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
