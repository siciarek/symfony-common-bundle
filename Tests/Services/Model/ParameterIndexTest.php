<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 10:03
 */

namespace Siciarek\SymfonyCommonBundle\Tests\Services\Model;

use Siciarek\SymfonyCommonBundle\Entity as E;
use Siciarek\SymfonyCommonBundle\Model\Parametrizable\ParametrizableInterface;
use Siciarek\SymfonyCommonBundle\Services\Model\Exceptions;
use Siciarek\SymfonyCommonBundle\Services\Model\ParameterIndex;
use Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity\DummyParametrizable;
use Siciarek\SymfonyCommonBundle\Tests\TestCase;

/**
 * Class ParameterIndexTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Model\
 *
 * @group service
 * @group parameter
 */
class ParameterIndexTest extends TestCase
{
    /**
     * @var ParameterIndex
     */
    protected $srv;

    public static function addNotOkProvider()
    {
        $owner = new DummyParametrizable();

        return [
            [
                $owner,
                'receive.messages.as.sms',
                true,
                E\Parameter::CATEGORY_COMMUNICATION,
                E\Parameter::VALUE_TYPE_BOOLEAN,
                ['notexisting' => 'true'],
                'Invalid parameter data.',
            ],
        ];
    }

    public static function addOkProvider()
    {
        $owner = new DummyParametrizable();

        return [
            [
                $owner,
                'receive.messages.as.emails',
                true,
                E\Parameter::CATEGORY_COMMUNICATION,
                E\Parameter::VALUE_TYPE_BOOLEAN,
                [],
                1,
            ],
            [
                $owner,
                'receive.messages.as.sms',
                true,
                E\Parameter::CATEGORY_COMMUNICATION,
                E\Parameter::VALUE_TYPE_BOOLEAN,
                ['defaultValue' => 'true'],
                2,
            ],
            [
                $owner,
                'publish.my.profile',
                true,
                E\Parameter::CATEGORY_GENERAL,
                E\Parameter::VALUE_TYPE_BOOLEAN,
                ['defaultValue' => 'true'],
                3,
            ],
        ];
    }

    public static function basicProvider()
    {
        return [
            [ParameterIndex::class, 'add'],
        ];
    }

    /**
     * @dataProvider basicProvider
     *
     * @param $class
     * @param $method
     */
    public function testBasic($class, $method)
    {
        $this->assertInstanceOf($class, $this->srv);
    }

    /**
     * @dataProvider addOkProvider
     */
    public function testAddOk(ParametrizableInterface $owner, $name, $value, $category, $valueType, $options, $count)
    {
        if ($owner->getParameterIndex() === null) {
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $em->persist($owner);
            $em->flush();
        }

        $actual = $this->srv->add($owner, $name, $value, $category, $valueType, $options);

        $this->assertTrue($actual);

        $parameterIndex = $owner->getParameterIndex();

        $this->assertInstanceOf(E\ParameterIndex::class, $parameterIndex);
        $this->assertEquals($count, $parameterIndex->getParameters()->count());

        return $owner;
    }

    /**
     * @dataProvider addOkProvider
     */
    public function testGetValueOk(
        ParametrizableInterface $owner,
        $name,
        $value,
        $category,
        $valueType,
        $options,
        $count
    ) {
        if ($owner->getParameterIndex() === null) {
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $em->persist($owner);
            $em->flush();
        }

        $actual = $this->srv->add($owner, $name, $value, $category, $valueType, $options);

        $this->assertTrue($actual);

        $expected = $value;

        $actual = $this->srv->getValue($owner, $name);

        $this->assertEquals($expected, $actual);

        return $owner;
    }

    /**
     * @expectedException \Siciarek\SymfonyCommonBundle\Services\Model\Exceptions\Parameter
     * @expectedExceptionMessage No such parameter: dummy.nonexistent.param
     */
    public function testGetValueNotOk()
    {
        $temp = $this->getContainer()->get('doctrine.orm.entity_manager')
            ->getRepository(DummyParametrizable::class)
            ->findAll();
        $owner = end($temp);

        $this->srv->getValue($owner, 'dummy.nonexistent.param');
    }

    /**
     * @dataProvider addNotOkProvider
     */
    public function testAddNotOk(
        ParametrizableInterface $owner,
        $name,
        $value,
        $category,
        $valueType,
        $options,
        $exceptionMessage
    ) {
        try {
            $this->srv->add($owner, $name, $value, $category, $valueType, $options);
            $this->fail('Exception should be thrown.');
        } catch (Exceptions\Parameter $exception) {
            $this->assertEquals($exceptionMessage, $exception->getMessage());
        }
    }

    public function setUp()
    {
        parent::setUp();
        $this->srv = $this->getContainer()->get('scb.model_parameter_index');
    }
}