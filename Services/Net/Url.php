<?php

namespace Siciarek\SymfonyCommonBundle\Services\Net;

use Siciarek\SymfonyCommonBundle\Services\Net\Exceptions\InvalidUrl;

/**
 * Class Url
 * @package Siciarek\SymfonyCommonBundle\Services\Net
 *
 * @method Url getScheme()
 * @method Url getHost()
 * @method Url getPort()
 * @method Url getUser()
 * @method Url getPass()
 * @method Url getPath()
 * @method Url getQuery()
 * @method Url getFragment()
 * @method Url getTld()
 */
class Url
{

    /**
     * Data storage
     * @var array
     */
    protected $data = null;

    /**
     * Empty frame
     * @var array
     */
    protected static $frame = [
        'scheme' => null,
        'host' => null,
        'port' => null,
        'user' => null,
        'pass' => null,
        'path' => null,
        'query' => null,
        'fragment' => null,
        'tld' => null,
    ];

    /**
     * Parse url
     *
     * @param string $url Given url
     * @return Url
     * @throws InvalidUrl
     */
    public function parse($url)
    {

        $temp = parse_url($url);

        foreach ($temp as $key => $val) {
            $val = trim($val);
            if (empty($val)) {
                unset($temp[$key]);
            }
        }

        if (count($temp) === 0) {
            throw new InvalidUrl();
        }

        $this->data = array_merge(self::$frame, $temp);

        $q = $this->data['query'];
        $q = str_replace('&amp;', '&', $q);

        parse_str($q, $query);
        $this->data['query'] = $query;

        if (!empty($this->data['host'])) {
            $x = explode('.', trim($this->data['host']));
            if (count($x) > 1) {
                $tld = array_pop($x);
                if (strlen($tld) > 0) {
                    $this->data['tld'] = $tld;
                }
            }

        }

        return $this;
    }

    /**
     * Returns DNS record data of host
     *
     * @return array DNS record data
     */
    public function getDnsRecord()
    {

        $record = dns_get_record($this->getHost());

        if (count($record) === 0) {
            throw new InvalidUrl('No dns record for given domain.');
        }

        return $record;
    }

    /**
     * Returns ip address given host is assigned to
     *
     * @return string  IPv4 address corresponding to a given Internet host name
     * @throws \Exception thrown when no ip was found with gethostbyname method
     */
    public function getIp()
    {

        $ip = gethostbyname($this->getHost());
        $ip = filter_var($ip, FILTER_VALIDATE_IP);

        if ($ip === false) {
            throw new InvalidUrl('No IPv4 address corresponding to a given Internet host name.');
        }

        return $ip;
    }

    /**
     * Returns entire parsed url data as array
     *
     * @return array
     * @throws InvalidUrl
     */
    public function getData()
    {

        if (!is_array($this->data)) {
            throw new InvalidUrl('Use parse() method first.');
        }

        return $this->data;
    }

    /**
     * Parsed url data element magic getter
     *
     * @param string $name
     * @param array $arguments
     * @return string
     * @throws InvalidUrl
     */
    public function __call($name, $arguments)
    {

        $key = preg_replace('/^get([A-Z][a-z]+)$/', '$1', $name);

        if (!($key !== $name and array_key_exists(strtolower($key), self::$frame))) {
            $msg = 'Call to undefined method '.__CLASS__.'::'.$name;
            throw new \Exception($msg);
        }

        $data = $this->getData();

        return $data[strtolower($key)];
    }
}
