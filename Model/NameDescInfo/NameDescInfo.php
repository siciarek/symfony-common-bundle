<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\NameDescInfo;

/**
 * NameDescInfo trait.
 *
 * Should be used inside entity where you need to describe entity with name, description and info.
 */
trait NameDescInfo
{
    use NameDescInfoProperties,
        NameDescInfoMethods;
}