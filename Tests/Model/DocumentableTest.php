<?php

namespace Siciarek\SymfonyCommonBundle\Tests\Model;

use Siciarek\SymfonyCommonBundle\Entity\DocumentFolder;
use Siciarek\SymfonyCommonBundle\Tests\TestCase;
use Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity as E;

/**
 * Class DocumentableTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Model\
 *
 * @group model
 */
class DocumentableTest extends TestCase
{
    public static function methodsProvider()
    {
        return [
            ['setDocumentFolder'],
            ['getDocumentFolder',],
        ];
    }

    /**
     * @dataProvider methodsProvider
     * @param $method
     */
    public function testMethodsExist($method)
    {
        $obj = new E\DummyDocumentable();
        $this->assertTrue(method_exists($obj, $method), $method);
    }

    public function testMethodsWorkProperely()
    {
        $obj = new E\DummyDocumentable();

        $documentFolder = new DocumentFolder();
        $obj->setDocumentFolder($documentFolder);

        $this->assertInstanceOf(DocumentFolder::class, $obj->getDocumentFolder());
        $this->assertEquals($documentFolder, $obj->getDocumentFolder());
    }
}