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
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * Sends http GET request to given url
     *
     * @param $url
     * @param null $data
     * @return mixed
     */
    public function get($url, $data = null);
    /**
     * Sends http POST request to given url
     *
     * @param $url
     * @param null $data
     * @return mixed
     */
    public function post($url, $data = null);
    /**
     * Sends http PUT request to given url
     *
     * @param $url
     * @param null $data
     * @return mixed
     */
    public function put($url, $data = null);
    /**
     * Sends http DLETE request to given url
     *
     * @param $url
     * @param null $data
     * @return mixed
     */
    public function delete($url, $data = null);
}