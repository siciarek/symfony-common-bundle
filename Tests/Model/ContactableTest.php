<?php

namespace Siciarek\SymfonyCommonBundle\Tests\Model;

use Siciarek\SymfonyCommonBundle\Tests\TestCase;
use Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity as E;
use Siciarek\SymfonyCommonBundle\Entity\ContactList;

/**
 * Class ContactableTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Model\
 *
 * @group model
 */
class ContactableTest extends TestCase
{
    public static function methodsProvider()
    {
        return [
            ['setContactList'],
            ['getContactList',],
        ];
    }

    /**
     * @dataProvider methodsProvider
     * @param $method
     */
    public function testMethodsExist($method)
    {
        $obj = new E\DummyContactable();
        $this->assertTrue(method_exists($obj, $method), $method);
    }

    public function testMethodsWorkProperely()
    {
        $obj = new E\DummyContactable();

        $contactList = new ContactList();
        $obj->setContactList($contactList);

        $this->assertInstanceOf(ContactList::class, $obj->getContactList());
        $this->assertEquals($contactList, $obj->getContactList());
    }
}