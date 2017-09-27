<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Addressable;

/**
 * Addressable trait.
 *
 * Should be used inside entity where you need to add addresses to entities
 */
trait AddressableMethods
{
    /**
     * Set addressBook
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\AddressBook $addressBook
     *
     * @return $this
     */
    public function setAddressBook(\Siciarek\SymfonyCommonBundle\Entity\AddressBook $addressBook = null)
    {
        $this->addressBook = $addressBook;

        return $this;
    }

    /**
     * Get addressBook
     *
     * @return \Siciarek\SymfonyCommonBundle\Entity\AddressBook
     */
    public function getAddressBook()
    {
        return $this->addressBook;
    }
}