<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 03.10.17
 * Time: 10:27
 */

namespace Siciarek\SymfonyCommonBundle\Tests\Services\Utils;

use Siciarek\SymfonyCommonBundle\Tests\TestCase;
use Siciarek\SymfonyCommonBundle\Services\Utils\Filter;
use Twig\Node\Expression\FilterExpression;

/**
 * Class FilterTest
 * @package Siciarek\SymfonyCommonBundle\Tests\Services\Utils
 *
 * @group service
 * @group filter
 */
class FilterTest extends TestCase
{
    /**
     * @var Filter
     */
    protected $srv;

    public static function basicProvider()
    {
        return [
            [Filter::class, 'add'],
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

    public static function sanitizeNotOkProvider()
    {
        return [
            [null, null],
            ['', null],
            ['ip', null],
            ['dummy', null],
        ];
    }

    public static function sanitizeOkProvider()
    {
        $text = <<<TXT
    
                 


        Pierwszy

jeden                  drugi

                      Zażółć gęślą jaźń.

trzeci


czwarty


ostatni






TXT;


        return [

            [Filter::SLUG, 'cv.programmer.en.odt', 'cv.programmer.en.odt', true],
            [Filter::SLUG, null, null, true],
            [Filter::SLUG, 'Zażółć Gęślą Jaźń', 'zazolc-gesla-jazn', true],

            [Filter::FACEBOOK_IDENTIFIER, '', null, true],
            [Filter::FACEBOOK_IDENTIFIER, 'penis', null, true],
            [Filter::FACEBOOK_IDENTIFIER, 'JacekSiciarek', 'JacekSiciarek', false],
            [Filter::FACEBOOK_IDENTIFIER, 'JacekSiciarek', 'jacek.siciarek', true],

            # Valid PL:
            [Filter::PHONE_NUMBER, '048603173114', '+48603173114', true],
            [Filter::PHONE_NUMBER, '048 603 173 114', '+48603173114', true],
            [Filter::PHONE_NUMBER, '48603173114', '+48603173114', true],
            [Filter::PHONE_NUMBER, '+48603173114', '+48603173114', true],
            [Filter::PHONE_NUMBER, '603173114', '+48603173114', true],
            [Filter::PHONE_NUMBER, '603 173 114', '+48603173114', true],
            [Filter::PHONE_NUMBER, '603-173-114', '+48603173114', true],
            [Filter::PHONE_NUMBER, '603.173.114', '+48603173114', true],
            [Filter::PHONE_NUMBER, '58 621 09 43', '+48586210943', true],
            [Filter::PHONE_NUMBER, '58 621-09-43', '+48586210943', true],
            [Filter::PHONE_NUMBER, '58 62-10-943', '+48586210943', true],

            [Filter::PHONE_NUMBER, '(58) 621-09-43', '+48586210943', true],
            [Filter::PHONE_NUMBER, '[58] 621-09-43', '+48586210943', true],
            [Filter::PHONE_NUMBER, '/58/ 621-09-43', '+48586210943', true],
            [Filter::PHONE_NUMBER, '(+4858) 621-09-43', '+48586210943', true],
            [Filter::PHONE_NUMBER, '(058) 621-09-43', '+48586210943', true],
            [Filter::PHONE_NUMBER, '058 621-09-43', '+48586210943', true],
            [Filter::PHONE_NUMBER, '058 621-09-43', '+48586210943', true],
            [Filter::PHONE_NUMBER, '0-58 621-09-43', '+48586210943', true],

            # Valid international:
            [Filter::PHONE_NUMBER, '+380988651455', '+380988651455', true],
            [Filter::PHONE_NUMBER, '+491754812222', '+491754812222', true],

            # Invalid:
            [Filter::PHONE_NUMBER, '', null, true],
            [Filter::PHONE_NUMBER, '-', null, true],
            [Filter::PHONE_NUMBER, 'brak', null, true],
            [Filter::PHONE_NUMBER, '0', null, true],
            [Filter::PHONE_NUMBER, '000-000-000', null, true],
            [Filter::PHONE_NUMBER, '+48000-000-000', null, true],
            [Filter::PHONE_NUMBER, '+486', null, true],
            [Filter::PHONE_NUMBER, '+4860', null, true],
            [Filter::PHONE_NUMBER, '+48603', null, true],
            [Filter::PHONE_NUMBER, '+486031', null, true],
            [Filter::PHONE_NUMBER, '+4860317', null, true],

            # Valid:
            [Filter::EMAIL_ADDRESS, '    SiCiareK@gmail.com       ', 'siciarek@gmail.com', true],
            [Filter::EMAIL_ADDRESS, '    siciarek@gmail.com       ', 'siciarek@gmail.com', true],
            [Filter::EMAIL_ADDRESS, '    sici arek@gmail.com       ', 'siciarek@gmail.com', true],
            [Filter::EMAIL_ADDRESS, '    sici arek@gm a i l.com       ', 'siciarek@gmail.com', true],
            [Filter::EMAIL_ADDRESS, 'siciarek@gmail.com', 'siciarek@gmail.com', true],
            [Filter::EMAIL_ADDRESS, 'sici@arek@gmail.com', null, true],

            # Invalid:
            [Filter::EMAIL_ADDRESS, 'siciarek2gmail.com', null, true],
            [Filter::EMAIL_ADDRESS, 'siciarek#gmail.com', null, true],
            [Filter::EMAIL_ADDRESS, 'siciarek@sqlmkalkdo.com', null, true],
            [Filter::EMAIL_ADDRESS, 'siciarek@m.com', null, true],

            [Filter::NOSPACE, '        Zażółć gęślą jąźń           !           ', 'Zażółćgęśląjąźń!', true],

            [Filter::STRING, '<p>Zażółć <strong>gęślą</strong> jaźń!</p>', 'Zażółć gęślą jaźń!', true],

            [Filter::NORMALIZE, $text, 'Pierwszy jeden drugi Zażółć gęślą jaźń. trzeci czwarty ostatni', true],

            [Filter::INT, 'A4', null, true],
            [Filter::INT, '4A', 4, true],
            [Filter::INT, '  2 345', 2345, true],
            [Filter::INT, '  2 345.00', 2345, true],
            [Filter::FLOAT, '  2 345.45', 2345.45, true],
            [Filter::FLOAT, '  X2 345.45', null, true],
            [Filter::FLOAT, '  2 345.45X', 2345.45, true],

            [Filter::NULL, null, null, true],
            [Filter::NULL, '   ', null, true],

            [Filter::TRIM, '    siciarek@gmail.com       ', 'siciarek@gmail.com', true],
            [Filter::ASCII, 'Zażółć gęślą jaźń', 'Zazolc gesla jazn', true],

            [Filter::LOWER, 'ZAŻÓŁĆ GĘŚLĄ JAŹŃ', 'zażółć gęślą jaźń', true],
            [Filter::UPPER, 'zażółć gęślą jaźń', 'ZAŻÓŁĆ GĘŚLĄ JAŹŃ', true],

            [Filter::ALPHANUM, 'zażółć gęślą jaźń 4', 'zaglja4', true],

            [Filter::IP4, '127.0.0.1', '127.0.0.1', true],
            [Filter::IP4, '327.0.0.1', null, true],

            [Filter::IP6, '2001:db8:a0b:12f0::1', '2001:db8:a0b:12f0::1', true],
            [Filter::IP6, '32001:db8:a0b:12f0::1', null, true],

        ];
    }

    /**
     * @dataProvider sanitizeOkProvider
     *
     * @param string|array $filter
     * @param null|string $value
     * @param null|string $expected
     */
    public function testSanitizeOk($filter, $value, $expected, $strict)
    {
        $actual = $this->srv->sanitize($value, $filter, $strict);

        if ($expected !== $actual) {
            file_put_contents('temp.expected.dat', $expected);
            file_put_contents('temp.actual.dat', $actual);
        }

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider sanitizeOkProvider
     *
     * @param string|array $filter
     * @param null|string $value
     * @param null|string $expected
     */
    public function testApplyFilterOk($filter, $value, $expected, $strict)
    {
        $actual = $this->srv->applyFilter($value, $filter, $strict);

        if ($expected !== $actual) {
            file_put_contents('temp.expected.dat', $expected);
            file_put_contents('temp.actual.dat', $actual);
        }

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider sanitizeNotOkProvider
     *
     * @param string|array $filter
     * @param null|string $value
     *
     * @expectedException \Siciarek\SymfonyCommonBundle\Services\Utils\Exceptions\Filter
     */
    public function testSanitizeNotOk($filter, $value)
    {
        $actual = $this->srv->sanitize($value, $filter);
    }

    public function setUp()
    {
        parent::setUp();
        $this->srv = $this->getContainer()->get('scb.utils_filter');
    }
}