<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 04.10.17
 * Time: 15:59
 */

namespace Siciarek\SymfonyCommonBundle\Services\Utils;


interface FilterInterface
{
    const ALPHANUM = 'alphanum';
    const ASCII = 'ascii';
    const EMAIL = 'email';
    const FACEBOOK_IDENTIFIER = 'facebook_identifier';
    const FLOAT = 'float';
    const INT = 'int';
    const IP4 = 'ip4';
    const IP6 = 'ip6';
    const LOWER = 'lower';
    const NORMALIZE = 'normalize';
    const NOSPACE = 'nospace';
    const NULL = 'null';
    const PHONE_NUMBER = 'phone_number';
    const STRING = 'string';
    const TRIM = 'trim';
    const UPPER = 'upper';

    const FILTERS = [
        self::ALPHANUM,
        self::ASCII,
        self::EMAIL,
        self::FACEBOOK_IDENTIFIER,
        self::FLOAT,
        self::INT,
        self::IP4,
        self::IP6,
        self::LOWER,
        self::NORMALIZE,
        self::NOSPACE,
        self::NULL,
        self::PHONE_NUMBER,
        self::STRING,
        self::TRIM,
        self::UPPER,
    ];

    /**
     * Applies filters on value.
     *
     * @param string $value
     * @param string|array $filters array of filters or filter string
     * @param bool $strict set on deeper validation.
     * @return null|string sanitized value or null if value is not valid
     * @throws Exceptions\Filter
     */
    public function sanitize($value, $filters, $strict = true);
}