<?php

namespace Siciarek\SymfonyCommonBundle\Tests\Model;

use Siciarek\SymfonyCommonBundle\Entity\AddressBook;
use Siciarek\SymfonyCommonBundle\Entity\ContactListEntry;
use Siciarek\SymfonyCommonBundle\Tests\TestCase;

/**
 * Class DescriptableTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Model\
 *
 * @group model
 */
class DescriptableTest extends TestCase
{
    public static function methodsProvider()
    {
        return [
            ['getInfo'],
            ['setInfo'],
            ['getDescription',],
            ['setDescription',],
        ];
    }

    /**
     * @dataProvider methodsProvider
     * @param $method
     */
    public function testMethodsExist($method)
    {
        $obj = new Entity\DummyDescriptable();
        $this->assertTrue(method_exists($obj, $method), $method);
    }

    public function testMethodsWorkProperely()
    {
        $description = 'Zażółć gęślą jaźń';
        $info =<<<INFO
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam id efficitur nibh. Donec vel libero vitae tellus dignissim scelerisque. Pellentesque ultrices tempus nisl, sed fringilla sem gravida non. Mauris nec felis et sapien condimentum aliquet id nec lorem. Aenean id metus ut quam luctus tempor. Aliquam placerat mauris elit, at convallis turpis condimentum ac. Mauris mi massa, rutrum eget sapien quis, pulvinar volutpat diam. Maecenas nec felis sit amet massa ultricies volutpat. Aliquam cursus suscipit pharetra. Sed molestie feugiat tempor. Pellentesque ultrices dui magna, porttitor tristique arcu sagittis sit amet. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Pellentesque ac dapibus elit, id aliquam elit. Etiam tempor nulla eget tellus vestibulum, vel rhoncus lectus consectetur.

Phasellus sit amet mauris a orci congue imperdiet. Vestibulum consectetur feugiat laoreet. Duis elementum tincidunt ligula, vel pretium sem sollicitudin eget. Donec eget sapien interdum felis luctus vestibulum ac non leo. Nunc libero elit, euismod sed gravida quis, efficitur vel urna. Sed a gravida risus. Aenean nulla elit, viverra sit amet cursus nec, placerat vitae elit. Integer vel velit eget turpis accumsan laoreet. Maecenas a felis nec enim ultrices ullamcorper.

Morbi pharetra vulputate ante, eu fringilla tortor facilisis et. Donec facilisis nunc ac risus vehicula imperdiet. Aenean dictum quam purus, ac convallis magna mollis non. Aenean porta mi ut urna elementum, vitae ullamcorper ligula eleifend. Integer vehicula neque risus. Praesent eu diam risus. Sed non tortor ac justo tempor venenatis in auctor sem.
INFO;

        $obj = new Entity\DummyDescriptable();

        $this->assertNull($obj->getDescription());
        $this->assertNull($obj->getInfo());

        $obj->setDescription($description);
        $obj->setInfo($info);

        $this->assertEquals($description, $obj->getDescription());
        $this->assertEquals($info, $obj->getInfo());
    }
}
