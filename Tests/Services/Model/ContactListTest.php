<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 10:03
 */

namespace Siciarek\SymfonyCommonBundle\Tests\Services\Model;

use Siciarek\SymfonyCommonBundle\Services\Model\ContactList;
use Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity\DummyContactable;
use Siciarek\SymfonyCommonBundle\Tests\TestCase;
use Siciarek\SymfonyCommonBundle\Entity as E;

/**
 * Class ContactListTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Model\
 *
 * @group service
 * @group contact
 */
class ContactListTest extends TestCase
{
    /**
     * @var ContactList
     */
    protected $srv;

    public static function addOkProvider()
    {
        return [
            [E\ContactListEntry::TYPE_FACEBOOK, 'jacek.siciarek',],
            [E\ContactListEntry::TYPE_FACEBOOK, 'jacek.siciarek',],
            [E\ContactListEntry::TYPE_FACEBOOK, 'jacek.siciarek',],
            [E\ContactListEntry::TYPE_FACEBOOK, 'jacek.siciarek',],
            [E\ContactListEntry::TYPE_FACEBOOK, 'jacek.siciarek',],
            [E\ContactListEntry::TYPE_FACEBOOK, 'jacek.siciarek',],
            [E\ContactListEntry::TYPE_FACEBOOK, 'jacek.siciarek',],
            [E\ContactListEntry::TYPE_FACEBOOK, 'jacek.siciarek',],
            [E\ContactListEntry::TYPE_FACEBOOK, 'jacek.siciarek',],
            [E\ContactListEntry::TYPE_FACEBOOK, 'jacek.siciarek',],
            [E\ContactListEntry::TYPE_PHONE, '+48603173114',],
            [E\ContactListEntry::TYPE_PHONE, '+48603173114',],
            [E\ContactListEntry::TYPE_PHONE, '+48603173114',],
            [E\ContactListEntry::TYPE_PHONE, '+48603173114',],
            [E\ContactListEntry::TYPE_PHONE, '+48603173114',],
            [E\ContactListEntry::TYPE_PHONE, '+48603173114',],
            [E\ContactListEntry::TYPE_PHONE, '+48603173900',],
            [E\ContactListEntry::TYPE_PHONE, '+48603173333',],
            [E\ContactListEntry::TYPE_PHONE, '+48511000111',],
            [E\ContactListEntry::TYPE_EMAIL, 'first@example.com',],
            [E\ContactListEntry::TYPE_EMAIL, 'second@example.com',],
            [E\ContactListEntry::TYPE_EMAIL, 'third@example.com',],
            [E\ContactListEntry::TYPE_EMAIL, 'fourth@example.com',],
            [E\ContactListEntry::TYPE_EMAIL, 'fourth@example.com',],
            [E\ContactListEntry::TYPE_EMAIL, 'fourth@example.com',],
            [E\ContactListEntry::TYPE_EMAIL, 'fourth@example.com',],
            [E\ContactListEntry::TYPE_EMAIL, 'fourth@example.com',],
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
            [ContactList::class, 'add'],
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

    public function testAddOk()
    {
        $owner = new DummyContactable();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->persist($owner);
        $em->flush();

        $entries = self::addOkProvider();

        $unique = [];
        foreach ($entries as $e) {
            $unique[implode('', $e)] = true;
        }

        foreach ($entries as $entry) {
            list($type, $value) = $entry;
            $actual = $this->srv->add($owner, $type, $value);
            $this->assertTrue($actual);
        }

        $this->assertNotEquals(count($entries), $owner->getContactList()->getEntries()->count());
        $this->assertEquals(count($unique), $owner->getContactList()->getEntries()->count());
    }

    public function setUp()
    {
        parent::setUp();
        $this->srv = $this->getContainer()->get('scb.model_contact_list');
    }
}