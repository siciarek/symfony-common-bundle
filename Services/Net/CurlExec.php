<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 09:57
 */

namespace Siciarek\SymfonyCommonBundle\Services\Net;


class CurlExec implements CurlExecInterface
{

    /**
     * Wrapps and execute curl actions
     *
     * @param array $opts
     * @param ResponseHeadersInterface $obj
     * @return array
     */
    public function exec(array $opts, ResponseHeadersInterface $obj)
    {
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $content = curl_exec($ch);
        $info = curl_getinfo($ch);
        $headers = $obj->getResponseHeaders();
        curl_close($ch);

        return [
            'content' => $content,
            'info' => $info,
            'headers' => $headers,
        ];
    }
}