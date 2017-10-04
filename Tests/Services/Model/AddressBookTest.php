<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 10:03
 */

namespace Siciarek\SymfonyCommonBundle\Tests\Services\Model;

use Siciarek\SymfonyCommonBundle\Services\Model\AddressBook;
use Siciarek\SymfonyCommonBundle\Services\Model\Exceptions\Address;
use Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity\DummyAddressable;
use Siciarek\SymfonyCommonBundle\Tests\TestCase;
use Siciarek\SymfonyCommonBundle\Entity as E;

/**
 * Class AddressBookTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Model\
 *
 * @group service
 * @group addr
 */
class AddressBookTest extends TestCase
{
    /**
     * @var AddressBook
     */
    protected $srv;

    public static function basicProvider()
    {
        return [
            [AddressBook::class, 'add'],
        ];
    }

    public static function addOkProvider()
    {
        $owner = new DummyAddressable();

        return [
            [$owner, ['address' => 'ul. Ejsmonda 4/35', 'postalCode' => '93-249', 'place' => 'Łódź']],
        ];
    }

    public static function addNotOkProvider()
    {
        $owner = new DummyAddressable();

        return [
            [$owner, [], 'No data given.'],
        ];
    }

    /**
     * @dataProvider basicProvider
     *
     * @param $class
     * @param $method
     */
    public function testBasic($class, $method)
    {
        $this->assertInstanceOf($class, $this->srv);
    }

    /**
     * @dataProvider addOkProvider
     */
    public function testAddOk($owner, $data)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->persist($owner);
        $em->flush();

        $condition = $this->srv->add($owner, $data);

        $this->assertTrue($condition);
    }

    /**
     * @dataProvider addNotOkProvider
     */
    public function testAddNotOk($owner, $data, $exceptionMessage)
    {

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->persist($owner);
        $em->flush();

        try {
            $this->srv->add($owner, $data);
            $this->fail('Exception should be thrown.');
        }
        catch(Address $exception) {
            $this->assertEquals($exceptionMessage, $exception->getMessage());
        }
    }

    public function setUp()
    {
        parent::setUp();
        $this->srv = $this->getContainer()->get('scb.model_address_book');
    }
}