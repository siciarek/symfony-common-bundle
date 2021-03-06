<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 09:44
 */

namespace Siciarek\SymfonyCommonBundle\Services\Net;


interface ResponseHeadersInterface
{
    /**
     * Returns http response headers.
     *
     * @return array
     */
    public function getResponseHeaders();
}