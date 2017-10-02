<?php

namespace Siciarek\SymfonyCommonBundle\Model\TimestampableOnCreate;

/**
 * TimestampableOnCreate trait.
 *
 * Should be used inside entity, that needs to be timestamped only when created,
 * otherwise use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable.
 */
trait TimestampableOnCreateMethods
{
    /**
     * Returns createdAt value.
     *
     * @return \DateTimeImmutable
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
