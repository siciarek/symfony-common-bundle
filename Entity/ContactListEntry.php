<?php

namespace Siciarek\SymfonyCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Siciarek\SymfonyCommonBundle\ContactListEntry
 *
 * @ORM\Entity
 * @ORM\Table(name="scb_contact_list_entry")
 * @ORM\Entity(repositoryClass="ContactListEntryRepository")
 */
class ContactListEntry {

    const TYPE_PHONE = 'phone';
    const TYPE_EMAIL = 'email';
    const TYPE_FACEBOOK = 'facebook';

    use ORMBehaviors\Blameable\Blameable,
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
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $main = false;

    /**
     * @ORM\Column()
     * @var string
     */
    private $type;

    /**
     * @ORM\Column()
     * @var string
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Siciarek\SymfonyCommonBundle\Entity\ContactList", inversedBy="entries")
     */
    private $list;

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
     * @return ContactListEntry
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
     * @return ContactListEntry
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
     * @return ContactListEntry
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
     * Set value
     *
     * @param string $value
     *
     * @return ContactListEntry
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set list
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\ContactList $list
     *
     * @return ContactListEntry
     */
    public function setList(\Siciarek\SymfonyCommonBundle\Entity\ContactList $list = null)
    {
        $this->list = $list;

        return $this;
    }

    /**
     * Get list
     *
     * @return \Siciarek\SymfonyCommonBundle\Entity\ContactList
     */
    public function getList()
    {
        return $this->list;
    }
}
