<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 09:44
 */

namespace Siciarek\SymfonyCommonBundle\Services\Net;


interface RestInterface
{
    public function get($url, $data = null);
    public function post($url, $data = null);
    public function put($url, $data = null);
    public function delete($url, $data = null);
}