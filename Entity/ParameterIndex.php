<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 09.10.17
 * Time: 23:36
 */

namespace Siciarek\SymfonyCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Siciarek\SymfonyCommonBundle\ParameterIndex
 *
 * @ORM\Entity
 * @ORM\Table(name="scb_parameter_index")
 * @ORM\Entity(repositoryClass="ParameterIndexRepository")
 */
class ParameterIndex {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Parameter", mappedBy="index", cascade={"persist"})
     */
    private $parameters;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parameters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add parameter
     *
     * @param  Parameter $parameter
     *
     * @return ParameterIndex
     */
    public function addParameter(Parameter $parameter)
    {
        $parameter->setIndex($this);
        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * Remove parameter
     *
     * @param  Parameter $parameter
     */
    public function removeParameter(Parameter $parameter)
    {
        $this->parameters->removeElement($parameter);
    }

    /**
     * Get parameters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}