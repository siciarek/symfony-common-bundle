<?php

namespace Siciarek\SymfonyCommonBundle\Services\Model\Exceptions;

class ContactListEntry extends \Exception
{
    public function __construct($message = 'Invalid contact list entry data.', $code = 404, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
