<?php

namespace Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity;

use Siciarek\SymfonyCommonBundle\Model as DBBehavior;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class DummyContactable implements DBBehavior\Contactable\ContactableInterface
{
    use DBBehavior\Contactable\Contactable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}