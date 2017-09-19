<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\ExtraData;

/**
* ExtraData trait.
*
* Should be used inside entity where you need to store some extradata (JSON serialized)
*/
trait ExtraData
{
    use ExtraDataProperties,
        ExtraDataMethods;
}