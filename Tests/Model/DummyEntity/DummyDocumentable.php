<?php

namespace Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity;

use Siciarek\SymfonyCommonBundle\Model as DBBehavior;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class DummyDocumentable implements DBBehavior\Documentable\DocumentableInterface
{
    use DBBehavior\Documentable\Documentable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
}