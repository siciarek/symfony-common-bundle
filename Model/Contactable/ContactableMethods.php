<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Contactable;

/**
 * Contactable trait.
 *
 * Should be used inside entity where you need to store contact data like email, phone etc.
 */
trait ContactableMethods
{
    /**
     * Set contactList
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\ContactList $contactList
     *
     * @return $this
     */
    public function setContactList(\Siciarek\SymfonyCommonBundle\Entity\ContactList $contactList = null)
    {
        $this->contactList = $contactList;

        return $this;
    }

    /**
     * Get contactList
     *
     * @return \Siciarek\SymfonyCommonBundle\Entity\ContactList
     */
    public function getContactList()
    {
        return $this->contactList;
    }
}