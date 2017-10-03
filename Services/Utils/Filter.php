<?php

namespace Siciarek\SymfonyCommonBundle\Services\Utils;

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
    const NULL = 'null';
    const STRING = 'string';
    const TRIM = 'trim';
    const UPPER = 'upper';

    const FILTERS = [
        self::ALPHANUM => true,
        self::ASCII => true,
        self::EMAIL => true,
        self::FLOAT => true,
        self::INT => true,
        self::IP4 => true,
        self::IP6 => true,
        self::LOWER => true,
        self::NORMALIZE => true,
        self::NULL => true,
        self::STRING => true,
        self::TRIM => true,
        self::UPPER => true,
    ];

    /**
     * Applies filter on value.
     *
     * @param string $value
     * @param string|array $filters
     * @return null|string
     * @throws Exceptions\Filter
     */
    public function sanitize($value, $filters)
    {
        $filters = (array)$filters;

        $filters = array_filter($filters);

        if (count($filters) === 0) {
            throw new Exceptions\Filter('No filter given.');
        }

        foreach ($filters as $filter) {
            if (!array_key_exists($filter, self::FILTERS)) {
                throw new Exceptions\Filter('No such filter: "'.$filter.'".');
            }

            $value = $this->applyFilter($value, $filter);
        }

        return $value ?: null;
    }

    private function applyFilter($value, $filter)
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
                $value = $this->applyFilter($value, [self::TRIM, self::NULL]);
                $value = preg_replace('/\s+/', '', $value);

                if ($value !== null) {
                    $value = intval($value);
                }

                return $value;

            case self::FLOAT:
                $value = $this->applyFilter($value, [self::NORMALIZE]);
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

            case self::EMAIL:
                $value = filter_var($value, FILTER_SANITIZE_EMAIL);
                $value = $this->applyFilter($value, self::LOWER);

                return $value;

            case self::STRING:
                $value = $this->applyFilter($value, self::TRIM);
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                $value = str_replace('\xc2\xa0', ' ', $value);

                return $value;
        }

        return $value;
    }
}
