<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Parametrizable;

/**
 * Parametrizable trait.
 *
 * Should be used inside entity where you need to store loosely coupled parameters.
 */
trait ParametrizableMethods
{
    /**
     * Set parameterIndex
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\ParameterIndex $parameterIndex
     *
     * @return $this
     */
    public function setParameterIndex(\Siciarek\SymfonyCommonBundle\Entity\ParameterIndex $parameterIndex = null)
    {
        $this->parameterIndex = $parameterIndex;

        return $this;
    }

    /**
     * Get parameterIndex
     *
     * @return \Siciarek\SymfonyCommonBundle\Entity\ParameterIndex
     */
    public function getParameterIndex()
    {
        return $this->parameterIndex;
    }
}