<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 19.09.17
 * Time: 18:58
 */

namespace Siciarek\SymfonyCommonBundle\Services\Model;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Runner\Exception;
use Siciarek\SymfonyCommonBundle\Entity as E;
use Siciarek\SymfonyCommonBundle\Model\Parametrizable\ParametrizableInterface;
use Siciarek\SymfonyCommonBundle\Services\Model\Exceptions;
use Siciarek\SymfonyCommonBundle\Services\Utils\FilterInterface;

class ParameterIndex
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * ContactList constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, FilterInterface $filter)
    {
        $this->entityManager = $entityManager;
        $this->filter = $filter;
    }

    /**
     * Returns of the owners prarameter recognized by $name
     *
     * @param ParametrizableInterface $owner
     * @param string $name
     */
    public function getValue(ParametrizableInterface $owner, $name) {
        $temp = $this->getAll($owner)->filter(function(E\Parameter $parameter) use ($name) {
             return $parameter->getName() === $name;
        });

        if($temp->count() === 0) {
            throw new Exceptions\Parameter('No such parameter: ' . $name);
        }

        /**
         * @var E\Parameter $parameter
         */
        $parameter = $temp->last();

        return json_decode($parameter->getValue());
    }

    /**
     * Returns all the prarameters of given owner.
     *
     * @param ParametrizableInterface $owner
     */
    public function getAll(ParametrizableInterface $owner) {
        return $owner->getParameterIndex()->getParameters();
    }

    /**
     * Adds address to owner's address book.
     *
     * @param ParametrizableInterface $owner
     * @param string $name
     * @param null|string $value
     * @param string $category
     * @param string $valueType
     * @param array $options
     * @return bool
     */
    public function add(
        ParametrizableInterface $owner,
        $name,
        $value,
        $category = E\Parameter::DEFAULT_CATEGORY,
        $valueType = E\Parameter::DEFAULT_VALUE_TYPE,
        array $options = []
    ) {
        # Validation:
        $keys = array_keys($options);
        $normalized = array_intersect($keys, ['type', 'defaultValue', 'valueConstraints', 'public', 'enabled']);
        $valid = (count($options) === 0  or count($keys) === count($normalized));

        if (false === $valid) {
            throw new Exceptions\Parameter('Invalid parameter data.');
        }

        $name = $this->filter->sanitize($name, [FilterInterface::NULL], true);

        $index = $owner->getParameterIndex();

        # If owner has no parameter index yet, set it up:
        if (false === $index instanceof E\ParameterIndex) {
            $index = new E\ParameterIndex();
            $owner->setParameterIndex($index);
            $this->entityManager->persist($owner);
        }

        $parameter = new E\Parameter();
        $parameter->setName($name);
        $parameter->setValue(json_encode($value));
        $parameter->setCategory($category);
        $parameter->setValueType($valueType);

        foreach ($options as $key => $val) {
            $setter = preg_replace_callback("/^(\w)(.*)/",
                function ($m) {
                    return 'set'.strtoupper($m[1]).$m[2];
                }, $key);

            $parameter->$setter($val);
        }

        $index->addParameter($parameter);

        $this->entityManager->persist($index);
        $this->entityManager->flush();

        # Return success:
        return true;
    }
}
