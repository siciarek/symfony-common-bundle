<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 09:57
 */

namespace Siciarek\SymfonyCommonBundle\Services\Net;


class Curl implements RestInterface, ResponseHeadersInterface
{
    const DEFAULT_COOKIE_FILE = 'COOKIES';

    protected $opts = [];
    protected $defaultHeaders = [];
    protected $auth = CURLAUTH_ANY;
    protected $responseHeaders = [];


    protected $requestHeaders = [];
    protected $response = [];

    /**
     * @var CurlExecInterface
     */
    protected $curlExec;

    /**
     * Returns curl exec command class
     *
     * @return CurlExecInterface
     */
    public function getCurlExec()
    {
        return $this->curlExec;
    }

    /**
     * Sets curl exec command class
     *
     * @param CurlExecInterface $curlExec
     */
    public function setCurlExec(CurlExecInterface $curlExec)
    {
        $this->curlExec = $curlExec;
    }

    /**
     * Curl constructor.
     *
     * @param null $tempdir
     * @param string $cookieName
     * @param bool $debug
     */
    public function __construct($tempdir = null, $cookieName = self::DEFAULT_COOKIE_FILE, $debug = false)
    {
        $tempdir = $tempdir ?: sys_get_temp_dir();

        if (!is_dir($tempdir)) {
            $umask = umask(0000);
            mkdir($tempdir, 0777, true);
            umask($umask);
        }

        $cookies = $tempdir.DIRECTORY_SEPARATOR.$cookieName;

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

        $this->setCurlExec(new CurlExec());
    }

    /**
     * Sends http GET request to given url
     *
     * @param string $url
     * @param null|mixed $data
     * @param bool $followlocation
     * @return array
     */
    public function get($url, $data = null, $followlocation = true)
    {
        $query = is_array($data) ? http_build_query($data) : $data;

        $url = trim($url);
        $url = rtrim($url, '?&');
        $parsedUrl = parse_url($url);

        if (!empty($query)) {
            $url .= !empty($parsedUrl['query']) ? '&' : '?';
            $url .= $query;
        }

        $opts = $this->opts;

        $opts[CURLOPT_FOLLOWLOCATION] = $followlocation;
        $opts[CURLOPT_HTTPGET] = true;
        $opts[CURLOPT_URL] = $url;

        return $this->exec($opts);
    }

    /**
     * Sends http POST request to given url
     *
     * @param string $url
     * @param null|mixed $data
     * @param bool $followlocation
     * @return array
     */
    public function post($url, $data = null, $followlocation = true)
    {
        $data = is_array($data) ? http_build_query($data) : $data;

        $opts = $this->opts;

        $opts[CURLOPT_FOLLOWLOCATION] = $followlocation;
        $opts[CURLOPT_POST] = true;
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_POSTFIELDS] = $data;

        return $this->exec($opts);
    }

    /**
     * Sends http PUT request to given url
     *
     * @param string $url
     * @param null|mixed $data
     * @param bool $followlocation
     * @return array
     */
    public function put($url, $data = null, $followlocation = true)
    {
        $fp = fopen('php://temp', 'w');
        if ($data !== null) {
            fwrite($fp, $data);
        }
        fseek($fp, 0);

        $opts = $this->opts;

        $opts[CURLOPT_FOLLOWLOCATION] = $followlocation;
        $opts[CURLOPT_PUT] = true;
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_INFILE] = $fp;
        $opts[CURLOPT_INFILESIZE] = strlen($data);

        return $this->exec($opts);
    }

    /**
     * Sends http DELETE request to given url
     *
     * @param string $url
     * @param null|mixed $data
     * @param bool $followlocation
     * @return array
     */
    public function delete($url, $data = null, $followlocation = true)
    {
        $data = is_array($data) ? http_build_query($data) : $data;

        $opts = $this->opts;

        $opts[CURLOPT_FOLLOWLOCATION] = $followlocation;
        $opts[CURLOPT_CUSTOMREQUEST] = RestInterface::METHOD_DELETE;
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_POSTFIELDS] = $data;

        return $this->exec($opts);
    }

    /**
     * Handler for processing response headers by curl
     *
     * @param $ch
     * @param string $header
     * @return int
     */
    public function headerFunction($ch, $header)
    {
        $match = [];

        if (preg_match('/^([^:]+):\s*(.*?)$/', $header, $match)) {
            $label = trim($match[1]);
            $value = trim($match[2]);

            if (isset($this->responseHeaders[$label])) {
                $this->responseHeaders[$label] = array_merge((array)$this->responseHeaders[$label], (array)$value);
            } else {
                $this->responseHeaders[$label] = $value;
            }
        }

        return strlen($header);
    }

    /**
     * Returns array of response headers
     *
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * @return array
     */
    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }

    /**
     * @param array $requestHeaders
     * @return Curl
     */
    public function setRequestHeaders($requestHeaders)
    {
        $this->requestHeaders = array_unique($requestHeaders);

        return $this;
    }

    /**
     * Direct exec
     *
     * @param array $opts
     * @return array
     */
    public function exec(array $opts)
    {
        $opts[CURLOPT_HTTPHEADER] = $this->getRequestHeaders();

        return $this->getCurlExec()->exec($opts, $this);
    }
}