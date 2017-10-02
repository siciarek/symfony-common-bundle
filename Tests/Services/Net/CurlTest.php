<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 10:03
 */

namespace Siciarek\SymfonyCommonBundle\Tests\Services\Net;

use Siciarek\SymfonyCommonBundle\Services\Net\Curl;

use Siciarek\SymfonyCommonBundle\Services\Net\CurlExecInterface;
use Siciarek\SymfonyCommonBundle\Services\Net\ResponseHeadersInterface;
use Siciarek\SymfonyCommonBundle\Services\Net\RestInterface;
use Siciarek\SymfonyCommonBundle\Tests\TestCase;

/**
 * Class CurlTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Net\
 *
 * @group service
 * @group curl
 */
class CurlTest extends TestCase
{
    const MOCK_API_URL = 'https://jsonplaceholder.typicode.com/posts';
    /**
     * @var CurlExecInterface $curlExecMock
     */
    private $curlExecMock;

    /**
     * @var Curl
     */
    protected $srv;

    public function testConstructor()
    {
        $tempdir = '/tmp';
        $cookieName = Curl::DEFAULT_COOKIE_FILE;
        $debug = false;

        $srv = new Curl();
        $srv->setCurlExec($this->curlExecMock);
        $resp = $srv->get(self::MOCK_API_URL);
        $opts = $resp['content'];

        $this->assertStringStartsWith($tempdir, $opts[CURLOPT_COOKIEJAR]);
        $this->assertStringStartsWith($tempdir, $opts[CURLOPT_COOKIEFILE]);
        $this->assertStringEndsWith($cookieName, $opts[CURLOPT_COOKIEJAR]);
        $this->assertStringEndsWith($cookieName, $opts[CURLOPT_COOKIEFILE]);
        $this->assertFalse($opts[CURLOPT_HEADER]);
        $this->assertFalse($opts[CURLOPT_VERBOSE]);

        $tempdir = '/tmp/' . time();
        $cookieName = 'CIASTECZKA';
        $debug = true;

        $srv = new Curl($tempdir, $cookieName, $debug);
        $srv->setCurlExec($this->curlExecMock);
        $resp = $srv->get(self::MOCK_API_URL);
        $opts = $resp['content'];

        $this->assertStringStartsWith($tempdir, $opts[CURLOPT_COOKIEJAR]);
        $this->assertStringStartsWith($tempdir, $opts[CURLOPT_COOKIEFILE]);
        $this->assertStringEndsWith($cookieName, $opts[CURLOPT_COOKIEJAR]);
        $this->assertStringEndsWith($cookieName, $opts[CURLOPT_COOKIEFILE]);
        $this->assertTrue($opts[CURLOPT_HEADER]);
        $this->assertTrue($opts[CURLOPT_VERBOSE]);
    }

    public function testHeadersMethods()
    {
        $headers = [
            'Content-Type: application/json',
            'X-Content-Type-Options:nosniff',
            'X-Content-Type-Options:nosniff',
            'X-Frame-Options:SameOrigin',
            'x-xss-protection:1; mode=block',
        ];

        $unique = array_unique($headers);


        $this->srv->setRequestHeaders($headers);

        $this->assertEquals(count($unique), count($this->srv->getRequestHeaders()));

        $ch = curl_init();

        foreach ($headers as $header) {
            $this->srv->headerFunction($ch, $header);
        }

        curl_close($ch);

        $responseHeaders = $this->srv->getResponseHeaders();

        $this->assertEquals(count($headers) - 1, count($responseHeaders));
    }

    public function urlsProvider()
    {
        return [
            [self::MOCK_API_URL, null],
            [self::MOCK_API_URL.'?number=1', null],
            [self::MOCK_API_URL, ['name' => 'joe']],
            [self::MOCK_API_URL.'?number=1', ['name' => 'joe']],
        ];
    }

    public function urlsProviderForPut()
    {
        return [
            [self::MOCK_API_URL, null],
            [self::MOCK_API_URL, 'Zażółć gęślą jaźń.'],
        ];
    }

    /**
     * @dataProvider urlsProvider
     */
    public function testGet($url, $query)
    {
        $result = $this->srv->get($url, $query);

        $actual = array_keys($result);
        $expected = ['content', 'info', 'headers'];
        $this->assertEquals($actual, $expected);

        $this->assertTrue($result['content'][CURLOPT_HTTPGET]);
        $this->assertFalse(isset($result['content'][CURLOPT_POST]));
        $this->assertFalse(isset($result['content'][CURLOPT_PUT]));
        $this->assertFalse(isset($result['content'][CURLOPT_CUSTOMREQUEST]) and $result['content'][CURLOPT_CUSTOMREQUEST] === RestInterface::METHOD_DELETE);
    }

    public function testPost()
    {
        $url = self::MOCK_API_URL;
        $result = $this->srv->post($url);

        $actual = array_keys($result);
        $expected = ['content', 'info', 'headers'];
        $this->assertEquals($actual, $expected);

        $this->assertFalse(isset($result['content'][CURLOPT_HTTPGET]));
        $this->assertTrue($result['content'][CURLOPT_POST]);
        $this->assertFalse(isset($result['content'][CURLOPT_PUT]));
        $this->assertFalse(isset($result['content'][CURLOPT_CUSTOMREQUEST]) and $result['content'][CURLOPT_CUSTOMREQUEST] === RestInterface::METHOD_DELETE);
    }

    /**
     * @dataProvider urlsProviderForPut
     */
    public function testPut($url, $data)
    {
        $result = $this->srv->put($url, $data);

        $actual = array_keys($result);
        $expected = ['content', 'info', 'headers'];
        $this->assertEquals($actual, $expected);

        $this->assertFalse(isset($result['content'][CURLOPT_HTTPGET]));
        $this->assertFalse(isset($result['content'][CURLOPT_POST]));
        $this->assertTrue($result['content'][CURLOPT_PUT]);
        $this->assertFalse(isset($result['content'][CURLOPT_CUSTOMREQUEST]) and $result['content'][CURLOPT_CUSTOMREQUEST] === RestInterface::METHOD_DELETE);
    }

    public function testDelete()
    {
        $url = self::MOCK_API_URL;
        $result = $this->srv->delete($url);

        $actual = array_keys($result);
        $expected = ['content', 'info', 'headers'];
        $this->assertEquals($actual, $expected);

        $this->assertFalse(isset($result['content'][CURLOPT_HTTPGET]));
        $this->assertFalse(isset($result['content'][CURLOPT_POST]));
        $this->assertFalse(isset($result['content'][CURLOPT_PUT]));
        $this->assertTrue(isset($result['content'][CURLOPT_CUSTOMREQUEST]) and $result['content'][CURLOPT_CUSTOMREQUEST] === RestInterface::METHOD_DELETE);
    }

    public function setUp()
    {
        parent::setUp();

        /**
         * @var CurlExecInterface $curlExecMock
         */
        $this->curlExecMock = $this->createMock(CurlExecInterface::class);
        $this->curlExecMock
            ->method('exec')
            ->will($this->returnCallback(function (array $opts, ResponseHeadersInterface $obj) {
                $url = $opts[CURLOPT_URL];

                $content = $opts;
                $info = [
                    'url' => $url,
                ];
                $headers = $obj->getResponseHeaders();

                return [
                    'content' => $content,
                    'info' => $info,
                    'headers' => $headers,
                ];
            }));

        $this->srv = $this->getContainer()->get('scb.net_curl');
        $this->srv->setCurlExec($this->curlExecMock);
    }
}