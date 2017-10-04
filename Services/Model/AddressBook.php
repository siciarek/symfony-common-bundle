<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 19.09.17
 * Time: 18:58
 */

namespace Siciarek\SymfonyCommonBundle\Services\Model;

use Doctrine\ORM\EntityManagerInterface;
use Siciarek\SymfonyCommonBundle\Entity as E;
use Siciarek\SymfonyCommonBundle\Model\Addressable\AddressableInterface;
use Siciarek\SymfonyCommonBundle\Services\Model\Exceptions\Address;
use Siciarek\SymfonyCommonBundle\Services\Utils\FilterInterface;

class AddressBook
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * ContactList constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, FilterInterface $filter)
    {
        $this->entityManager = $entityManager;
        $this->filter = $filter;
    }

    /**
     * Adds address to owner's address book.
     *
     * @param AddressableInterface $owner
     * @param array $data address data
     * @return bool
     */
    public function add(AddressableInterface $owner, array $data = [])
    {
        # Validation:
        if(count($data) === 0) {
            throw new Address('No data given.');
        }

        # If owner has no address book yet, set it up:
        if (false === $owner->getAddressBook() instanceof E\AddressBook) {
            $owner->setAddressBook(new E\AddressBook());
            $this->entityManager->persist($owner);
            $this->entityManager->flush();
        }

        # Return success:
        return true;
    }
}
