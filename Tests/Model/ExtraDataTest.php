<?php

namespace Siciarek\SymfonyCommonBundle\Tests\Model;

use Siciarek\SymfonyCommonBundle\Entity\AddressBook;
use Siciarek\SymfonyCommonBundle\Entity\ContactListEntry;
use Siciarek\SymfonyCommonBundle\Tests\TestCase;

/**
 * Class ExtraDataTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Model\
 *
 * @group model
 */
class ExtraDataTest extends TestCase
{
    public static function methodsProvider()
    {
        return [
            ['getData'],
            ['setData'],
        ];
    }

    /**
     * @dataProvider methodsProvider
     * @param $method
     */
    public function testMethodsExist($method)
    {
        $obj = new Entity\DummyExtraData();
        $this->assertTrue(method_exists($obj, $method), $method);
    }

    public function testMethodsWorkProperely()
    {
        $data = [
            'John' => 'Lennon',
            'Paul' => 'McCartney',
            'George' => 'Harrison',
            'Ringo' => 'Starr',
        ];

        $obj = new Entity\DummyExtraData();

        $this->assertNull($obj->getData());

        $obj->setData($data);

        $this->assertEquals($data, $obj->getData());
    }
}
