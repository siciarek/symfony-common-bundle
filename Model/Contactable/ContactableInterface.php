<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Contactable;

/**
 * Contactable interface.
 *
 * Should be implemented by entity where you need to add contacts to entities
 */
interface ContactableInterface
{
    /**
     * Set contactList
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\ContactList $contactList
     *
     * @return $this
     */
    public function setContactList(\Siciarek\SymfonyCommonBundle\Entity\ContactList $contactList = null);

    /**
     * Get contactList
     *
     * @return \Siciarek\SymfonyCommonBundle\Entity\ContactList
     */
    public function getContactList();
}