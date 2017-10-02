<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 19.09.17
 * Time: 18:58
 */

namespace Siciarek\SymfonyCommonBundle\Services\Model;

use Doctrine\ORM\EntityManagerInterface;
use Siciarek\SymfonyCommonBundle\Model\Contactable\ContactableInterface;
use Siciarek\SymfonyCommonBundle\Entity as E;

class ContactListEntry
{

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * ContactListEntry constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Add contact to owners contact list.
     *
     * @param ContactableInterface $owner
     * @param string $type contact list entry type
     * @param string $value contact list entry value
     * @return bool returns if operation succeed
     */
    public function add(ContactableInterface $owner, $type, $value)
    {

        # Type validation:
        if (false === in_array($type, E\ContactListEntry::AVAILABLE_TYPES)) {
            throw new Exceptions\ContactListEntry('Invalid contact list entry type: '.$type);
        }

        # If owner has no contact list yet, create it:
        if (false === $owner->getContactList() instanceof E\ContactList) {
            $owner->setContactList(new E\ContactList());
            $this->entityManager->persist($owner);
            $this->entityManager->flush();
        }


        # TODO: value validation
        # TODO: if value of given type already exists do noting and return success

        $exists = $owner->getContactList()->getEntries()->filter(function (E\ContactListEntry $entry) use (
            $type,
            $value
        ) {
            return $entry->getType() === $type && $entry->getValue() === $value;
        });

        if ($exists->count() > 0) {
            return true;
        }

        $entry = new E\ContactListEntry();
        $entry->setType($type);
        $entry->setValue($value);
        $owner->getContactList()->addEntry($entry);

        $this->entityManager->persist($owner);

        $currentListOfType = $owner->getContactList()->getEntries()->filter(function (E\ContactListEntry $entry) use (
            $type
        ) {
            return $entry->getType() === $type;
        });

        foreach ($currentListOfType as $e) {
            $main = $e->getType() === $type && $e->getValue() === $value;
            $e->setMain($main);
            $this->entityManager->persist($e);
        }

        $this->entityManager->flush();

        return true;
    }
}
