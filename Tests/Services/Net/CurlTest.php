<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 10:03
 */

namespace Siciarek\SymfonyCommonBundle\Tests\Services\Net;

use Siciarek\SymfonyCommonBundle\Services\Net\Curl;
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

        $this->assertEquals(200, $result['info']['http_code']);
    }

    public function testPost()
    {
        $url = self::MOCK_API_URL;
        $result = $this->srv->post($url);

        $actual = array_keys($result);
        $expected = ['content', 'info', 'headers'];
        $this->assertEquals($actual, $expected);

        $this->assertEquals(201, $result['info']['http_code'], $result['content']);
    }

    public function testPut()
    {
        $url = self::MOCK_API_URL;
        $result = $this->srv->put($url);

        $actual = array_keys($result);
        $expected = ['content', 'info', 'headers'];
        $this->assertEquals($actual, $expected);

        $this->assertEquals(404, $result['info']['http_code'], $result['content']);
    }

    public function testDelete()
    {
        $url = self::MOCK_API_URL;
        $result = $this->srv->delete($url);

        $actual = array_keys($result);
        $expected = ['content', 'info', 'headers'];
        $this->assertEquals($actual, $expected);

        $this->assertEquals(404, $result['info']['http_code'], $result['content']);
    }

    public function setUp()
    {
        $this->srv = new Curl();
    }
}