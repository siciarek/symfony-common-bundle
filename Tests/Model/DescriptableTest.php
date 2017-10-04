<?php

namespace Siciarek\SymfonyCommonBundle\Tests\Model;

use Siciarek\SymfonyCommonBundle\Tests\TestCase;
use Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity as E;

/**
 * Class DescriptableTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Model\
 *
 * @group model
 */
class DescriptableTest extends TestCase
{
    public static function methodsProvider()
    {
        return [
            ['getDescription',],
            ['setDescription',],
        ];
    }

    /**
     * @dataProvider methodsProvider
     * @param $method
     */
    public function testMethodsExist($method)
    {
        $obj = new E\DummyDescriptable();
        $this->assertTrue(method_exists($obj, $method), $method);
    }

    public function testMethodsWorkProperely()
    {
        $description = 'Zażółć gęślą jaźń';

        $obj = new E\DummyDescriptable();

        $this->assertNull($obj->getDescription());

        $obj->setDescription($description);

        $this->assertEquals($description, $obj->getDescription());
    }
}
