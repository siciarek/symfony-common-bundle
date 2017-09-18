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
trait Contactable
{
    use ContactableProperties,
        ContactableMethods;
}