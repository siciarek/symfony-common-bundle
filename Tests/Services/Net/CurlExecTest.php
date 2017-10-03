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
 * Class CurlExecTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Net\
 *
 * @group service
 * @group curl
 */
class CurlExecTest extends TestCase
{
    public function testExec()
    {
        $curl = new Curl();
        $resp = $curl->get(CurlTest::MOCK_API_URL);

        $expected = ['content', 'info', 'headers'];
        $actual = array_keys($resp);

        $this->assertEquals($expected, $actual);

        $this->assertTrue(is_array($resp['info']));
        $this->assertTrue(is_array($resp['headers']));
    }
}