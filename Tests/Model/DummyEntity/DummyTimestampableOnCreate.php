<?php

namespace Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity;

use Siciarek\SymfonyCommonBundle\Model as DBBehavior;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class DummyTimestampableOnCreate
{
    use DBBehavior\TimestampableOnCreate\TimestampableOnCreate;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
}