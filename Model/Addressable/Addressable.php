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
trait Addressable
{
    use AddressableProperties,
        AddressableMethods;
}