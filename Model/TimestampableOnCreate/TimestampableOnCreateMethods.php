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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
