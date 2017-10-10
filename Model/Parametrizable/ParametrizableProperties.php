<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Parametrizable;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parametrizable trait.
 *
 * Should be used inside entity where you need to store loosely coupled parameters.
 */
trait ParametrizableProperties
{
    /**
     * @ORM\OneToOne(targetEntity="Siciarek\SymfonyCommonBundle\Entity\ParameterIndex", cascade={"persist"})
     */
    protected $parameterIndex;
}