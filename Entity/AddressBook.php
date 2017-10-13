<?php

namespace Siciarek\SymfonyCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Siciarek\SymfonyCommonBundle\Model as DBBehaviors;

/**
 * Siciarek\SymfonyCommonBundle\AddressBook
 *
 * @ORM\Entity
 * @ORM\Table(name="scb_address_book")
 * @ORM\Entity(repositoryClass="AddressBookRepository")
 */
class AddressBook {

   /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Siciarek\SymfonyCommonBundle\Entity\Address", mappedBy="book")
     */
    private $addresses;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add address
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\Address $address
     *
     * @return AddressBook
     */
    public function addAddress(\Siciarek\SymfonyCommonBundle\Entity\Address $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\Address $address
     */
    public function removeAddress(\Siciarek\SymfonyCommonBundle\Entity\Address $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }
}
