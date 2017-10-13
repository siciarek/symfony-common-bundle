<?php

namespace Siciarek\SymfonyCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Siciarek\SymfonyCommonBundle\Model as DBBehaviors;

/**
 * Siciarek\SymfonyCommonBundle\Parameter
 *
 * @ORM\Entity
 * @ORM\Table(name="scb_parameter")
 * @ORM\Entity(repositoryClass="ParameterRepository")
 */
class Parameter
{
    const CATEGORY_GENERAL = 'general';
    const CATEGORY_ACCESS = 'access';
    const CATEGORY_COMMUNICATION = 'communication';

    const CATEGORIES = [
        self::CATEGORY_GENERAL,
        self::CATEGORY_ACCESS,
        self::CATEGORY_COMMUNICATION,
    ];

    const VALUE_TYPE_STRING = 'string';
    const VALUE_TYPE_INTEGER = 'integer';
    const VALUE_TYPE_DECIMAL = 'decimal';
    const VALUE_TYPE_BOOLEAN = 'boolean';

    const VALUE_TYPES = [
        self::VALUE_TYPE_STRING,
        self::VALUE_TYPE_BOOLEAN,
        self::VALUE_TYPE_INTEGER,
        self::VALUE_TYPE_DECIMAL,
    ];

    const DEFAULT_CATEGORY = self::CATEGORY_GENERAL;
    const DEFAULT_VALUE_TYPE = self::VALUE_TYPE_STRING;

    const VALUE_BOOLEAN_TRUE = 'true';
    const VALUE_BOOLEAN_FALSE = 'false';

    use DBBehaviors\Descriptable\Descriptable,
        ORMBehaviors\Blameable\Blameable,
        ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\SoftDeletable\SoftDeletable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column()
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    private $value;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $enabled = true;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $public = false;

    /**
     * @ORM\Column()
     * @var string
     */
    private $category = self::DEFAULT_CATEGORY;

    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    private $defaultValue;

    /**
     * @ORM\Column()
     * @var string
     */
    private $valueType = self::DEFAULT_VALUE_TYPE;

    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    private $valueConstraints;

    /**
     * @ORM\ManyToOne(targetEntity="ParameterIndex", inversedBy="parameters", cascade={"persist"})
     */
    private $index;

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
     * Set name
     *
     * @param string $name
     *
     * @return Parameter
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Parameter
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Parameter
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set public
     *
     * @param boolean $public
     *
     * @return Parameter
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Parameter
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set defaultValue
     *
     * @param string $defaultValue
     *
     * @return Parameter
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * Get defaultValue
     *
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Set valueType
     *
     * @param string $valueType
     *
     * @return Parameter
     */
    public function setValueType($valueType)
    {
        $this->valueType = $valueType;

        return $this;
    }

    /**
     * Get valueType
     *
     * @return string
     */
    public function getValueType()
    {
        return $this->valueType;
    }

    /**
     * Set valueConstraints
     *
     * @param string $valueConstraints
     *
     * @return Parameter
     */
    public function setValueConstraints($valueConstraints)
    {
        $this->valueConstraints = $valueConstraints;

        return $this;
    }

    /**
     * Get valueConstraints
     *
     * @return string
     */
    public function getValueConstraints()
    {
        return $this->valueConstraints;
    }

    /**
     * Set index
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\ParameterIndex $index
     *
     * @return Parameter
     */
    public function setIndex(ParameterIndex $index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get index
     *
     * @return \Siciarek\SymfonyCommonBundle\Entity\ParameterIndex
     */
    public function getIndex()
    {
        return $this->index;
    }
}
