<?php

namespace Siciarek\SymfonyCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Siciarek\SymfonyCommonBundle\Model as DBBehaviors;

/**
 * Siciarek\SymfonyCommonBundle\Entity\Address
 *
 * @ORM\Entity
 * @ORM\Table(name="scb_address")
 * @ORM\Entity(repositoryClass="AddressRepository")
 */
class Address
{
    const TYPE_GENERAL = 'general';
    const TYPE_CORRESPONDENCE = 'correspondence';
    const TYPE_LOCATION = 'location';
    const TYPE_ACCOMMODATION = 'accommodation';
    const TYPE_INVOICE = 'invoice';
    const TYPE_DELIVERY = 'delivery';

    const AVAILABLE_TYPES = [
        self::TYPE_GENERAL,
        self::TYPE_CORRESPONDENCE,
        self::TYPE_LOCATION,
        self::TYPE_ACCOMMODATION,
        self::TYPE_INVOICE,
        self::TYPE_DELIVERY,
    ];

    use DBBehaviors\Descriptable\Descriptable,
        ORMBehaviors\Geocodable\Geocodable,
        ORMBehaviors\Sortable\Sortable,
        ORMBehaviors\Blameable\Blameable,
        ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\SoftDeletable\SoftDeletable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Siciarek\SymfonyCommonBundle\Entity\AddressBook", inversedBy="addresses")
     * @var AddressBook
     */
    private $book;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $enabled = true;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $main = false;

    /**
     * @ORM\Column()
     * @var string
     */
    private $type = self::TYPE_GENERAL;

    /**
     * @ORM\Column(nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column()
     */
    private $place;


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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Address
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set main
     *
     * @param boolean $main
     *
     * @return Address
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return boolean
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Address
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Address
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return Address
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set place
     *
     * @param string $place
     *
     * @return Address
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set book
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\AddressBook $book
     *
     * @return Address
     */
    public function setBook(\Siciarek\SymfonyCommonBundle\Entity\AddressBook $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \Siciarek\SymfonyCommonBundle\Entity\AddressBook
     */
    public function getBook()
    {
        return $this->book;
    }
}
