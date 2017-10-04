<?php

namespace Siciarek\SymfonyCommonBundle\Services\Utils;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;

/**
 * Filter service
 *
 * Class Filter
 * @package Siciarek\SymfonyCommonBundle\Services\Utils
 */
class Filter
{
    const ALPHANUM = 'alphanum';
    const ASCII = 'ascii';
    const EMAIL = 'email';
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
     * @var array
     */
    protected $options;

    /**
     * Filter constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;

        if ($options === []) {
            $this->options = [
                self::EMAIL => [
                    'lowercase' => true,
                ],
                self::PHONE_NUMBER => [
                    'defaultRegion' => 'PL',
                    'numberFormat' => PhoneNumberFormat::E164,
                ],
            ];
        }
    }

    /**
     * Applies filter on value.
     *
     * @param string $value
     * @param string|array $filters
     * @param bool $strict set on deeper validation.
     * @return null|string
     * @throws Exceptions\Filter
     */
    public function sanitize($value, $filters, $strict = true)
    {
        $filters = array_filter((array)$filters);

        if (count($filters) === 0) {
            throw new Exceptions\Filter('No filter given.');
        }

        foreach ($filters as $filter) {
            if (false === in_array($filter, self::FILTERS)) {
                throw new Exceptions\Filter('No such filter: "'.$filter.'".');
            }

            $value = $this->applyFilter($value, $filter, $strict);
        }

        return $value ?: null;
    }

    /**
     * Applies filter on given value
     *
     * @param string $value
     * @param string $filter
     * @param bool $strict set on deeper validation.
     * @return mixed|string
     */
    public function applyFilter($value, $filter, $strict = true)
    {
        if ($value === null) {
            return $value;
        }

        switch ($filter) {
            case self::ASCII:
                $value = transliterator_transliterate('Any-Latin; Latin-ASCII', $value);

                return $value;

            case self::LOWER:
                $value = mb_convert_case($value, MB_CASE_LOWER, 'UTF-8');

                return $value;

            case self::UPPER:
                $value = mb_convert_case($value, MB_CASE_UPPER, 'UTF-8');

                return $value;

            case self::ALPHANUM:
                $value = preg_replace('/[^a-zA-Z0-9]+/', '', $value);
                break;

            case self::TRIM:
                $value = trim($value);

                return $value;

            case self::NULL:
                $value = trim($value);
                $value = strlen($value) === 0 ? null : $value;

                return $value;

            case self::NORMALIZE:
                $value = str_replace('\xc2\xa0', ' ', $value);
                $value = trim($value);
                $value = preg_replace('/\s+/', ' ', $value);

                return $value;

            case self::INT:
                $value = str_replace('\xc2\xa0', '', $value);
                $value = $this->sanitize($value, [self::TRIM, self::NULL]);
                $value = preg_replace('/\s+/', '', $value);

                if ($value !== null) {
                    $value = intval($value);
                }

                return $value;

            case self::FLOAT:
                $value = $this->sanitize($value, [self::NORMALIZE]);
                $value = preg_replace('/\s+/', '', $value);

                if ($value !== null) {
                    $value = floatval($value);
                }

                return $value;

            case self::IP4:
                $value = trim($value);
                $value = filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);

                return $value;

            case self::IP6:
                $value = trim($value);
                $value = filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);

                return $value;

            case self::NOSPACE:
                $value = str_replace('\xc2\xa0', ' ', $value);
                $value = preg_replace('/\s+/', '', $value);

                return $value;

            case self::PHONE_NUMBER:

                $options = $this->options[$filter];

                $value = trim($value);
                $prefix = mb_substr($value, 0, 1);

                # 048603173114
                if($prefix !== '+') {
                    $value = preg_replace('/^\D*0\D*/', '', $value);
                }

                $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();

                try {
                    $phoneNumberObject = $phoneNumberUtil->parse($value, $options['defaultRegion']);
                } catch (NumberParseException $exception) {
                    return null;
                }

                if (false === $phoneNumberUtil->isPossibleNumber($phoneNumberObject)) {
                    return null;
                }

                if ((int)$phoneNumberObject->getNationalNumber() === 0) {
                    return null;
                }

                $value = $phoneNumberUtil->format($phoneNumberObject, $options['numberFormat']);

                return $value;

            case self::EMAIL:
                $options = $this->options[$filter];

                $value = filter_var($value, FILTER_SANITIZE_EMAIL);

                if (true === $options['lowercase']) {
                    $value = $this->sanitize($value, self::LOWER);
                }

                $validator = Validation::createValidator();

                $violations = $validator->validate($value, [
                    new Email(['strict' => $strict, 'checkMX' => $strict]),
                ]);

                return $violations->count() > 0 ? null : $value;

            case self::STRING:
                $value = $this->sanitize($value, self::TRIM);
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                $value = str_replace('\xc2\xa0', ' ', $value);

                return $value;
        }

        return $value;
    }
}
