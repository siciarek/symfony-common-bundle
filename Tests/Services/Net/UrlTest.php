<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 10:03
 */

namespace Siciarek\SymfonyCommonBundle\Tests\Services\Net;

use Siciarek\SymfonyCommonBundle\Tests\TestCase;
use Siciarek\SymfonyCommonBundle\Services\Net\Url;

/**
 * Class UrlTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Net
 *
 * @group url
 */
class UrlTest extends TestCase
{
    const SIMPLE_URL = 'http://google.com';
    const COMPLEX_URL = 'https://username:password@application.dev:8080/user/dashboard?form[firstName]=John&amp;form[lastName]=Doe&amp;choice[]=1&amp;choice[]=2&amp;title=Za%C5%BC%C3%B3%C5%82%C4%87+g%C4%99%C5%9Bl%C4%85+ja%C5%BA%C5%84&amp;id=1024#page-bottom';
    const NONEXISTENT_URL = 'http://surfinglivers.com';

    /**
     * @var Url
     */
    protected $srv;

    public static function parseOkProvider()
    {
        return [
            [
                'Simple http url',
                self::SIMPLE_URL,
                [
                    'scheme' => 'http',
                    'host' => 'google.com',
                    'port' => null,
                    'user' => null,
                    'pass' => null,
                    'path' => null,
                    'query' => [],
                    'fragment' => null,
                    'tld' => 'com',
                ],
            ],
            [
                'Complex https url',
                self::COMPLEX_URL,
                [
                    'scheme' => 'https',
                    'host' => 'application.dev',
                    'port' => 8080,
                    'user' => 'username',
                    'pass' => 'password',
                    'path' => '/user/dashboard',
                    'query' => [
                        'form' => [
                            'firstName' => 'John',
                            'lastName' => 'Doe',
                        ],
                        'choice' => [1, 2],
                        'title' => 'Zażółć gęślą jaźń',
                        'id' => 1024,
                    ],
                    'fragment' => 'page-bottom',
                    'tld' => 'dev',
                ],
            ],
        ];
    }

    public static function getIpProvider()
    {
        return [
            [
                'Siciarek Online',
                'http://siciarek.pl',
                '85.17.184.27',
            ],
        ];
    }

    public static function gettersProvider()
    {
        return [
            ['getScheme'],
            ['getHost'],
            ['getPort'],
            ['getUser'],
            ['getPass'],
            ['getPath'],
            ['getQuery'],
            ['getFragment'],
            ['getTld'],
        ];
    }

    /**
     * @dataProvider parseOkProvider
     *
     * @param $message
     * @param $url
     * @param $expected
     */
    public function testParseOk($message, $url, $expected)
    {
        $actual = $this->srv->parse($url)->getData();
        $this->assertEquals($expected, $actual, $message);
    }

    /**
     * @dataProvider getIpProvider
     *
     * @param $message
     * @param $url
     * @param $expected
     */
    public function testGetIp($message, $url, $expected)
    {
        $actual = $this->srv->parse($url)->getIp();
        $this->assertStringStartsWith($expected, $actual, $message);
    }

    public function testGetDnsRecord()
    {
        $url = self::SIMPLE_URL;
        $actual = $this->srv->parse($url)->getDnsRecord();
        $this->assertNotNull($actual);
    }

    public function testGetters()
    {
        $temp = self::parseOkProvider();
        $temp = array_filter($temp, function ($e) {
            return $e[1] = self::COMPLEX_URL;
        });
        $temp = array_values($temp)[0];

        $url = $temp[1];
        $data = $temp[2];
        $keys = array_keys($data);

        foreach ($keys as $key) {
            $method = 'get'.mb_convert_case($key, MB_CASE_TITLE);
            $actual = $this->srv->parse($url)->$method();
            $expected = $data[$key];
            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * @expectedException \Siciarek\SymfonyCommonBundle\Services\Net\Exceptions\InvalidUrl
     * @expectedExceptionMessage Use parse() method first.
     * @expectedExceptionCode 404
     */
    public function testExceptionGetData()
    {
        $this->srv->getData();
    }

    /**
     * @dataProvider gettersProvider
     * @expectedException \Siciarek\SymfonyCommonBundle\Services\Net\Exceptions\InvalidUrl
     * @expectedExceptionMessage Use parse() method first.
     * @expectedExceptionCode 404
     *
     * @param $method
     */
    public function testExceptionGetters($method)
    {
        $this->srv->$method();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Call to undefined method Siciarek\SymfonyCommonBundle\Services\Net\Url::getDummy
     * @expectedExceptionCode 0
     */
    public function testExceptionNonexistentGetter()
    {
        $this->srv->getDummy();
    }

    /**
     * @expectedException \Siciarek\SymfonyCommonBundle\Services\Net\Exceptions\InvalidUrl
     * @expectedExceptionMessage No dns record for given domain.
     * @expectedExceptionCode 404
     */
    public function testExceptionGetDnsRecord()
    {
        $url = self::NONEXISTENT_URL;
        $this->srv->parse($url)->getDnsRecord();
    }

    public function setUp()
    {
        $this->srv = new Url();
    }
}