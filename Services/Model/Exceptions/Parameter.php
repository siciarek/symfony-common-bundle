<?php

namespace Siciarek\SymfonyCommonBundle\Services\Model\Exceptions;

class Parameter extends \Exception
{
    public function __construct($message = 'Invalid parameter definition.', $code = 404, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
