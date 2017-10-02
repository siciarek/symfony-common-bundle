<?php

namespace Siciarek\SymfonyCommonBundle\Model\TimestampableOnCreate;

use Doctrine\ORM\Mapping as ORM;

/**
 * TimestampableOnCreate trait.
 *
 * Should be used inside entity, that needs to be timestamped only when created,
 * otherwise use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable.
 */
trait TimestampableOnCreateProperties
{
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @var \DateTimeImmutable
     */
    protected $createdAt;
}
