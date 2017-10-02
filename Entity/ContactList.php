<?php

namespace Siciarek\SymfonyCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Siciarek\SymfonyCommonBundle\Model\Descriptable\Descriptable;

/**
 * Siciarek\SymfonyCommonBundle\ContactList
 *
 * @ORM\Entity
 * @ORM\Table(name="scb_contact_list")
 * @ORM\Entity(repositoryClass="ContactList")
 */
class ContactList {

    use Descriptable,
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
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $enabled = true;

    /**
     * @ORM\OneToMany(targetEntity="Siciarek\SymfonyCommonBundle\Entity\ContactListEntry", mappedBy="list")
     */
    private $entries;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entries = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return ContactList
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
     * Add entry
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\ContactListEntry $entry
     *
     * @return ContactList
     */
    public function addEntry(\Siciarek\SymfonyCommonBundle\Entity\ContactListEntry $entry)
    {
        $entry->setList($this);
        $this->entries[] = $entry;

        return $this;
    }

    /**
     * Remove entry
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\ContactListEntry $entry
     */
    public function removeEntry(\Siciarek\SymfonyCommonBundle\Entity\ContactListEntry $entry)
    {
        $this->entries->removeElement($entry);
    }

    /**
     * Get entries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntries()
    {
        return $this->entries;
    }
}
