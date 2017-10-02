<?php

namespace Siciarek\SymfonyCommonBundle\Model\TimestampableOnCreate;

/**
 * TimestampableOnCreate trait.
 *
 * Should be used inside entity, that needs to be timestamped only when created,
 * otherwise use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable.
 */
trait TimestampableOnCreate
{
    use TimestampableOnCreateProperties,
        TimestampableOnCreateMethods;
}
