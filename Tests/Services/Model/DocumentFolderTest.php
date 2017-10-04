<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 10:03
 */

namespace Siciarek\SymfonyCommonBundle\Tests\Services\Model;

use Siciarek\SymfonyCommonBundle\Services\Model\DocumentFolder;
use Siciarek\SymfonyCommonBundle\Services\Model\Exceptions\Document;
use Siciarek\SymfonyCommonBundle\Tests\Model\DummyEntity\DummyDocumentable;
use Siciarek\SymfonyCommonBundle\Tests\TestCase;
use Siciarek\SymfonyCommonBundle\Entity as E;

/**
 * Class DocumentFolderTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Model\
 *
 * @group service
 * @group doc
 */
class DocumentFolderTest extends TestCase
{
    /**
     * @var DocumentFolder
     */
    protected $srv;

    public static function basicProvider()
    {
        return [
            [DocumentFolder::class, 'add'],
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

    public static function addOkProvider()
    {
        $owner = new DummyDocumentable();
        $filename = '/tmp/dummy.file.dat';
        $content = 'Hello, World!';
        file_put_contents($filename, $content);
        return [
            [$owner, $filename, null],
            [$owner, $filename, 'New Document'],
        ];
    }

    public static function addNotOkProvider()
    {
        $owner = new DummyDocumentable();

        return [
            [$owner, '/tmp/nonexiting.file.dat', null, 'Invalid path: /tmp/nonexiting.file.dat'],
        ];
    }

    /**
     * @dataProvider addOkProvider
     */
    public function testAddOk($owner, $pathToFile, $title)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->persist($owner);
        $em->flush();

        $condition = $this->srv->add($owner, $pathToFile, $title);

        $this->assertTrue($condition);
    }

    /**
     * @dataProvider addNotOkProvider
     */
    public function testAddNotOk($owner, $pathToFile, $title, $exceptionMessage)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->persist($owner);
        $em->flush();

        try {
            $this->srv->add($owner, $pathToFile, $title);
            $this->fail('Exception should be thrown.');
        }
        catch(Document $exception) {
            $this->assertEquals($exceptionMessage, $exception->getMessage());
        }
    }

    public function setUp()
    {
        parent::setUp();
        $this->srv = $this->getContainer()->get('scb.model_document_folder');
    }
}