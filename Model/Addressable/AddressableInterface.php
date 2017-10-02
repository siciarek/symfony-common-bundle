<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Addressable;

/**
 * Addressable interface.
 *
 * Should be implemented by entity where you need to add addresses to entities
 */
interface AddressableInterface
{
    /**
     * Set addressBook
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\AddressBook $addressBook
     *
     * @return $this
     */
    public function setAddressBook(\Siciarek\SymfonyCommonBundle\Entity\AddressBook $addressBook = null);

    /**
     * Get addressBook
     *
     * @return \Siciarek\SymfonyCommonBundle\Entity\AddressBook
     */
    public function getAddressBook();
}