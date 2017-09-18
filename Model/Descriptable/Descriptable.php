<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Descriptable;

/**
 * Descriptable trait.
 *
 * Should be used inside entity where you need to store extra description (short) and info (longer)
 */
trait Descriptable
{
    use DescriptableProperties,
        DescriptableMethods;
}