<?php

namespace Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity;

use Siciarek\SymfonyCommonBundle\Model as DBBehavior;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class DummyAddressable implements DBBehavior\Addressable\AddressableInterface
{
    use DBBehavior\Addressable\Addressable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
}