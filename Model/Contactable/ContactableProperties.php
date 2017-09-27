<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Contactable;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contactable trait.
 *
 * Should be used inside entity where you need to store contact data like email, phone etc.
 */
trait ContactableProperties
{
    /**
     * @ORM\OneToOne(targetEntity="Siciarek\SymfonyCommonBundle\Entity\ContactList")
     */
    protected $contactList;
}