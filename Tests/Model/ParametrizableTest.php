<?php

namespace Siciarek\SymfonyCommonBundle\Tests\Model;

use Siciarek\SymfonyCommonBundle\Entity\ParameterIndex;
use Siciarek\SymfonyCommonBundle\Tests\TestCase;
use Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity as E;

/**
 * Class ParametrizableTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Model\
 *
 * @group model
 * @group parameter
 */
class ParametrizableTest extends TestCase
{
    public static function methodsProvider()
    {
        return [
            ['setParameterIndex'],
            ['getParameterIndex',],
        ];
    }

    /**
     * @dataProvider methodsProvider
     * @param $method
     */
    public function testMethodsExist($method)
    {
        $obj = new E\DummyParametrizable();
        $this->assertTrue(method_exists($obj, $method), $method);
    }

    public function testMethodsWorkProperely()
    {
        $obj = new E\DummyParametrizable();

        $parameterIndex = new ParameterIndex();
        $obj->setParameterIndex($parameterIndex);

        $this->assertInstanceOf(ParameterIndex::class, $obj->getParameterIndex());
        $this->assertEquals($parameterIndex, $obj->getParameterIndex());
    }
}