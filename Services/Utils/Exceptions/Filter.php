<?php

namespace Siciarek\SymfonyCommonBundle\Services\Utils\Exceptions;

class Filter extends \Exception
{
    public function __construct($message = 'Invalid value.', $code = 404, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
