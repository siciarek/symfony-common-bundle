<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 09:44
 */

namespace Siciarek\SymfonyCommonBundle\Services\Net;

interface CurlExecInterface
{
    /**
     * Wrapps and execute curl actions
     *
     * @param array $opts
     * @param HeadersInterface $obj
     * @return array
     */
    public function exec(array $opts, HeadersInterface $obj);
}