<?php

namespace Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity;

use Siciarek\SymfonyCommonBundle\Model as DBBehavior;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class DummyParametrizable implements DBBehavior\Parametrizable\ParametrizableInterface
{
    use DBBehavior\Parametrizable\Parametrizable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
}