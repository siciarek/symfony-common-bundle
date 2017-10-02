<?php

namespace Siciarek\SymfonyCommonBundle\Services\Model\Exceptions;

class Address extends \Exception
{
    public function __construct($message = 'Invalid address data.', $code = 404, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
