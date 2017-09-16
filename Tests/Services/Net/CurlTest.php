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
    /**
     * @var
     */
    protected $obj;

    public function testGet()
    {
        $this->assertTrue(true);
    }

    public function testPost()
    {
        $this->assertTrue(true);
    }

    public function testPut()
    {
        $this->assertTrue(true);
    }

    public function testDelete()
    {
        $this->assertTrue(true);
    }

    public function setUp()
    {
        $this->obj = new Curl();
    }
}