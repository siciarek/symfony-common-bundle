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
    protected $response = [];

    /**
     * Curl constructor.
     *
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
            CURLOPT_HEADERFUNCTION => [$this, 'headerFunction'],
        ];
    }

    public function get($url, $data = null)
    {
        $query = is_array($data) ? http_build_query($data) : $data;

        $url = trim($url);
        $url = rtrim($url, '?&');
        $parsedUrl = parse_url($url);

        $url .= !empty($parsedUrl['query']) ? '&' : '?';
        $url .= $query;

        $opts = $this->opts;

        $opts[CURLOPT_HTTPGET] = true;
        $opts[CURLOPT_URL] = $url;

        return $this->exec($opts);
    }

    public function post($url, $data = null)
    {
        $data = is_array($data) ? http_build_query($data) : $data;

        $opts = $this->opts;

        $opts[CURLOPT_POST] = true;
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_POSTFIELDS] = $data;

        return $this->exec($opts);
    }

    public function put($url, $data = null)
    {
        $fp = fopen('php://temp', 'w');
        if ($data !== null) {
            fwrite($fp, $data);
        }
        fseek($fp, 0);

        $opts = $this->opts;

        $opts[CURLOPT_PUT] = true;
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_INFILE] = $fp;
        $opts[CURLOPT_INFILESIZE] = strlen($data);

        return $this->exec($opts);
    }

    public function delete($url, $data = null)
    {
        $data = is_array($data) ? http_build_query($data) : $data;

        $opts = $this->opts;

        $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_POSTFIELDS] = $data;

        return $this->exec($opts);
    }

    /**
     * @param $ch
     * @param $header
     * @return int
     */
    protected function headerFunction($ch, $header)
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

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Direct exec
     *
     * @param array $opts
     * @return array
     */
    public function exec(array $opts)
    {
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $content = curl_exec($ch);
        $info = curl_getinfo($ch);
        $headers = $this->getHeaders();
        curl_close($ch);

        $this->response = [
            'content' => $content,
            'info' => $info,
            'headers' => $headers,
        ];

        return $this->response;
    }
}