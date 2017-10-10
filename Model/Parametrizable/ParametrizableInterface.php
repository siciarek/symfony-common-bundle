<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Parametrizable;

/**
 * Parametrizable interface.
 *
 * Should be used inside entity where you need to store loosely coupled parameters.
  */
interface ParametrizableInterface
{
    /**
     * Set parameter index
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\ParameterIndex $parameterIndex
     *
     * @return $this
     */
    public function setParameterIndex(\Siciarek\SymfonyCommonBundle\Entity\ParameterIndex $parameterIndex = null);

    /**
     * Get parameter index
     *
     * @return \Siciarek\SymfonyCommonBundle\Entity\ParameterIndex
     */
    public function getParameterIndex();
}