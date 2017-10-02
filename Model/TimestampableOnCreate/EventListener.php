<?php

namespace Siciarek\SymfonyCommonBundle\Model\TimestampableOnCreate;

use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class EventListener
 *
 * @package Siciarek\SymfonyCommonBundle\Model\TimestampableOnCreate\EventListener
 *
 * Usage:
 *
 * Add to your Resources/config/services.yml
 *
 * services:
 *
 *     Siciarek\SymfonyCommonBundle\Model\TimestampableOnCreate\EventListener:
 *         tags:
 *             - { name: doctrine.event_listener, event: prePersist, connection: default }
 */
class EventListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        /**
         * @var TimestampableOnCreate $entity
         */
        $entity = $args->getEntity();

        if (array_key_exists(TimestampableOnCreate::class, class_uses($entity))) {

            // Create a datetime with microseconds
            $dateTime = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', microtime(true)));
            $dateTime->setTimezone(new \DateTimeZone(date_default_timezone_get()));

            if (null === $entity->getCreatedAt()) {
                $entity->setCreatedAt($dateTime);
            }
        }
    }
}