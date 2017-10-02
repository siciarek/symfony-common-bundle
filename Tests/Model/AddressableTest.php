<?php

namespace Siciarek\SymfonyCommonBundle\Tests\Model;

use Siciarek\SymfonyCommonBundle\Entity\AddressBook;
use Siciarek\SymfonyCommonBundle\Tests\TestCase;
use Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity as E;

/**
 * Class AddressableTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Model\
 *
 * @group model
 */
class AddressableTest extends TestCase
{
    public static function methodsProvider()
    {
        return [
            ['setAddressBook'],
            ['getAddressBook',],
        ];
    }

    /**
     * @dataProvider methodsProvider
     * @param $method
     */
    public function testMethodsExist($method)
    {
        $obj = new E\DummyAddressable();
        $this->assertTrue(method_exists($obj, $method), $method);
    }

    public function testMethodsWorkProperely()
    {
        $obj = new E\DummyAddressable();

        $addressBook = new AddressBook();
        $obj->setAddressBook($addressBook);

        $this->assertInstanceOf(AddressBook::class, $obj->getAddressBook());
        $this->assertEquals($addressBook, $obj->getAddressBook());
    }
}