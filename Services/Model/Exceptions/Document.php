<?php

namespace Siciarek\SymfonyCommonBundle\Services\Model\Exceptions;

class Document extends \Exception
{
    public function __construct($message = 'Invalid document.', $code = 404, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
