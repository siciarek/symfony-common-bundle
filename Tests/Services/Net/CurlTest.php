<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 10:03
 */

namespace Siciarek\SymfonyCommonBundle\Tests\Services\Net;

use Siciarek\SymfonyCommonBundle\Services\Net\Curl;
use Siciarek\SymfonyCommonBundle\Services\Net\CurlExec;
use Siciarek\SymfonyCommonBundle\Services\Net\CurlExecInterface;
use Siciarek\SymfonyCommonBundle\Services\Net\HeadersInterface;
use Siciarek\SymfonyCommonBundle\Services\Net\RestInterface;
use Siciarek\SymfonyCommonBundle\Tests\TestCase;

class CurlTest extends TestCase
{
    const MOCK_API_URL = 'https://jsonplaceholder.typicode.com/posts';

    /**
     * @var Curl
     */
    protected $srv;

    public function testGet()
    {
        $url = self::MOCK_API_URL;
        $result = $this->srv->get($url);

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

    public function testPut()
    {
        $url = self::MOCK_API_URL;
        $result = $this->srv->put($url);

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
        $curlExecMock = $this->createMock(CurlExecInterface::class);
        $curlExecMock
            ->method('exec')
            ->will($this->returnCallback(function (array $opts, HeadersInterface $obj) {
                $url = $opts[CURLOPT_URL];

                $content = $opts;
                $info = [
                    'url' => $url,
                ];
                $headers = $obj->getHeaders();

                return [
                    'content' => $content,
                    'info' => $info,
                    'headers' => $headers,
                ];
            }));

        $this->srv = new Curl();
        $this->srv->setCurlExec($curlExecMock);
    }
}