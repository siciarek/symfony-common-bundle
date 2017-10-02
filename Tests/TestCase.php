<?php

namespace Siciarek\SymfonyCommonBundle\Tests;


use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BasicTestCase;
use Symfony\Component\DependencyInjection\Container;


class TestCase extends BasicTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function setUp()
    {
        if (false === $this->container instanceof Container) {
            $this->container = self::createClient()->getContainer();
        }
    }
}