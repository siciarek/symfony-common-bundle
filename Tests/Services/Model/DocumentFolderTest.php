<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 16.09.17
 * Time: 10:03
 */

namespace Siciarek\SymfonyCommonBundle\Tests\Services\Model;

use Siciarek\SymfonyCommonBundle\Services\Model\DocumentFolder;
use Siciarek\SymfonyCommonBundle\Tests\TestCase;
use Siciarek\SymfonyCommonBundle\Entity as E;

/**
 * Class ContactListTest
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

    public function setUp()
    {
        parent::setUp();
        $this->srv = $this->getContainer()->get('scb.model_document_folder');
    }
}