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

class ContactList
{

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * ContactList constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Validate entry
     *
     * @param string $type contact list entry type
     * @param string $value contact list entry value
     * @param bool $strict if true strict value validation is processed
     * @throws Exceptions\ContactListEntry
     */
    public function validate($type, $value, $strict = false)
    {
        switch ($type) {
            case E\ContactListEntry::TYPE_EMAIL:
                # TODO: validate value if not valid - throw new Exceptions\ContactList('Invalid email: ' . $value);
                break;
            case E\ContactListEntry::TYPE_PHONE:
                # TODO: validate value if not valid - throw new Exceptions\ContactList('Invalid phone number: ' . $value);
                break;
            case E\ContactListEntry::TYPE_FACEBOOK:
                # TODO: validate value if not valid - throw new Exceptions\ContactList('Invalid facebook identifier: ' . $value);
                break;
        }
    }

    /**
     * Add contact to owners contact list.
     *
     * @param ContactableInterface $owner
     * @param string $type contact list entry type
     * @param string $value contact list entry value
     * @param bool $strict if true strict value validation is processed
     * @return bool returns if operation succeed
     * @throws Exceptions\ContactListEntry
     */
    public function add(ContactableInterface $owner, $type, $value, $strict = false)
    {
        # Sanitize data:
        $type = trim($type);
        $value = trim($value);

        # Validate type:
        if (false === in_array($type, E\ContactListEntry::AVAILABLE_TYPES)) {
            throw new Exceptions\ContactListEntry('Invalid contact list entry type: '.$type);
        }

        # If owner has no contact list yet, set it up:
        if (false === $owner->getContactList() instanceof E\ContactList) {
            $owner->setContactList(new E\ContactList());
            $this->entityManager->persist($owner);
            $this->entityManager->flush();
        }

        # Validate value:
        $this->validate($type, $value, $strict);

        # If value of given type already exists do noting and return success
        $exists = $owner
            ->getContactList()
            ->getEntries()
            ->filter(function (E\ContactListEntry $entry) use (
                $type,
                $value
            ) {
                return $entry->getType() === $type && $entry->getValue() === $value;
            });

        if ($exists->count() > 0) {
            return true;
        }

        # Create entry
        $entry = new E\ContactListEntry();
        $entry->setType($type);
        $entry->setValue($value);
        $owner->getContactList()->addEntry($entry);

        $this->entityManager->persist($owner);

        # Set current entry main in scope of the type:
        $currentListOfType = $owner
            ->getContactList()
            ->getEntries()
            ->filter(function (E\ContactListEntry $entry) use ($type) {
                return $entry->getType() === $type;
            });

        foreach ($currentListOfType as $e) {
            $main = $e->getType() === $type && $e->getValue() === $value;
            $e->setMain($main);
            $this->entityManager->persist($e);
        }

        # Persist in database:
        $this->entityManager->flush();

        # Return success:
        return true;
    }
}
