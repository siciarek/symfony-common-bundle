<?php

namespace Siciarek\SymfonyCommonBundle\Tests\Model;

use Siciarek\SymfonyCommonBundle\Tests\TestCase;
use Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity as E;

/**
 * Class TimestampableOnCreateTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Model\
 *
 * @group tsmodel
 */
class TimestampableOnCreateTest extends TestCase
{
    public static function methodsProvider()
    {
        return [
            ['setCreatedAt'],
            ['getCreatedAt'],
        ];
    }

    /**
     * @dataProvider methodsProvider
     * @param $method
     */
    public function testMethodsExist($method)
    {
        $obj = new E\DummyTimestampableOnCreate();
        $this->assertTrue(method_exists($obj, $method), $method);
    }

    public function testMethodsWorkProperely()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $obj = new E\DummyTimestampableOnCreate();

        $this->assertNull($obj->getCreatedAt());

        $em->persist($obj);

        $this->assertNotNull($obj->getCreatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $obj->getCreatedAt());
    }
}
