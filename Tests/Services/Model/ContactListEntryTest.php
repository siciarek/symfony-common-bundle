<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 10:03
 */

namespace Siciarek\SymfonyCommonBundle\Tests\Services\Model;

use Siciarek\SymfonyCommonBundle\Services\Model\ContactListEntry;
use Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity\DummyContactable;
use Siciarek\SymfonyCommonBundle\Tests\TestCase;
use Siciarek\SymfonyCommonBundle\Entity as E;
/**
 * Class ContactListEntryTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Model\
 *
 * @group service
 * @group cle
 */
class ContactListEntryTest extends TestCase
{
    /**
     * @var ContactListEntry
     */
    protected $srv;

    public static function addOkProvider()
    {
        $owner = new DummyContactable();

        return [
            [$owner, E\ContactListEntry::TYPE_PHONE, '+48603173114',],
            [$owner, E\ContactListEntry::TYPE_EMAIL, 'siciarek@gmail.com',],
            [$owner, E\ContactListEntry::TYPE_FACEBOOK, 'jacek.siciarek',],
        ];
    }

    public static function addNotOkProvider()
    {
        $owner = new DummyContactable();

        return [
            [$owner, null, 'http://siciarek.pl',],
            [$owner, '', 'http://siciarek.pl',],
            [$owner, ' ', 'http://siciarek.pl',],
            [$owner, '-', 'http://siciarek.pl',],
            [$owner, 'www', 'http://siciarek.pl',],
        ];
    }

    public static function basicProvider()
    {
        return [
            [ContactListEntry::class, 'add'],
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
     * @dataProvider addNotOkProvider
     * @expectedException \Siciarek\SymfonyCommonBundle\Services\Model\Exceptions\ContactListEntry
     * @expectedExceptionMessageRegExp '^Invalid contact list entry'
     */
    public function testAddNotOk($owner, $type, $value)
    {
        $this->srv->add($owner, $type, $value);
    }

    /**
     * @dataProvider addOkProvider
     */
    public function testAddOk($owner, $type, $value)
    {
        $actual = $this->srv->add($owner, $type, $value);

        $this->assertTrue($actual);
    }



    public function setUp()
    {
        parent::setUp();
        $this->srv = $this->getContainer()->get('scb.model_contact');
    }
}