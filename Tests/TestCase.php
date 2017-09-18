<?php

namespace Siciarek\SymfonyCommonBundle\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BasicTestCase;
use Symfony\Component\DependencyInjection\Container;


class TestCase extends BasicTestCase {

    /**
     * @var Container
     */
    protected $container;

    /**
     * @return Container
     */
    public function getContainer() {

        if(false === $this->container instanceof Container) {
            $this->container = self::createClient()->getContainer();
        }
        return $this->container;
    }
}