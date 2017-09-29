<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\ExtraData;

use Doctrine\ORM\Mapping as ORM;

/**
* ExtraData trait.
*
* Should be used inside entity where you need to store some extradata (JSON serialized)
*/
trait ExtraDataProperties
{
    /**
     * @ORM\Column(type="json", nullable="true")
     */
    protected $data;
}