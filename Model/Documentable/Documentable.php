<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Documentable;

/**
 * Documentable trait.
 *
 * Should be used inside entity where you need to store documents
 */
trait Documentable
{
    use DocumentableProperties,
        DocumentableMethods;
}