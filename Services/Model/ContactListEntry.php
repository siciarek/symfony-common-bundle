<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 19.09.17
 * Time: 18:58
 */

namespace Siciarek\SymfonyCommonBundle\Services\Model;

use Siciarek\SymfonyCommonBundle\Model\Contactable\ContactableInterface;
use Siciarek\SymfonyCommonBundle\Entity as E;

class ContactListEntry
{
    /**
     * Add contact to owners contact list.
     *
     * @param ContactableInterface $owner
     * @param string $type contact list entry type
     * @param string $value contact list entry value
     * @return bool returns if operation succeed
     */
    public function add(ContactableInterface $owner, $type, $value) {

        if(false === in_array($type, E\ContactListEntry::AVAILABLE_TYPES)) {
            throw new Exceptions\ContactListEntry('Invalid contact list entry type: ' . $type);
        }

        $entry = new E\ContactListEntry();
        $entry->setType($type);
        $entry->setValue($value);
        $entry->setList($owner->getContactList());

        return true;
    }
}
