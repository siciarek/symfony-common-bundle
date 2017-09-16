<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 09:57
 */

namespace Siciarek\SymfonyCommonBundle\Services\Net;


class Curl implements RestInterface
{
    protected $opts = [];
    protected $defaultHeaders = [];
    protected $auth = CURLAUTH_ANY;
    protected $headers = [];

    /**
     * Curl constructor.
     * @param null $tempdir
     * @param string $name
     * @param bool $debug
     */
    public function __construct($tempdir = null, $name = 'COOKIES', $debug = false)
    {
        $tempdir = $tempdir ?: sys_get_temp_dir();

        if (!is_dir($tempdir)) {
            $umask = umask(0000);
            mkdir($tempdir, 0777, true);
            umask($umask);
        }

        $cookies = $tempdir . DIRECTORY_SEPARATOR . $name;

        if (file_exists($cookies)) {
            @unlink($cookies);
        }

        $this->opts = [
            CURLOPT_HTTPHEADER => $this->defaultHeaders,
            CURLOPT_COOKIEFILE => $cookies,
            CURLOPT_COOKIEJAR => $cookies,
            CURLOPT_COOKIESESSION => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER => $debug,
            CURLOPT_VERBOSE => $debug,
            CURLOPT_HEADERFUNCTION => [$this, 'headersHandler'],
        ];
    }

    public function get($url, $data = null)
    {
        $data = is_array($data) ? http_build_query($data) : $data;

        $url = trim($url, '?&');
        $parsedUrl = parse_url($url);
        if (!empty($parsedUrl['query'])) {
            $url .= '&' . $data;
        } else {
            $url .= '?' . $data;
        }

        return $this->exit();
    }

    public function post($url, $data = null)
    {
        // TODO: Implement post() method.
    }

    public function put($url, $data = null)
    {
        // TODO: Implement put() method.
    }

    public function delete($url, $data = null)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $ch
     * @param $header
     * @return int
     */
    protected function headersHandler($ch, $header)
    {
        $match = [];

        if (preg_match('/^([^:]+):\s*(.*?)$/', $header, $match)) {
            $label = trim($match[1]);
            $value = trim($match[2]);

            if (isset($this->headers[$label])) {
                $this->headers[$label] = array_merge((array)$this->headers[$label], (array)$value);
            } else {
                $this->headers[$label] = $value;
            }
        }

        return strlen($header);
    }

    protected function exec()
    {
        $ch = curl_init();
        curl_setopt_array($ch, $this->opts);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $headers = $this->getHeaders();
        curl_close($ch);

        $this->response = [
            'content' => $content,
            'info' => $info,
            'headers' => $headers,
        ];

        return $this;
    }
}