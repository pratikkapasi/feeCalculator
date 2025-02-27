<?php

namespace Lendable\Interview\Interpolation\Service\Fee;

/**
 * This class defines the fee structure for different terms.
 *
 * Class Structure
 *
 * @package Lendable\Interview\Interpolation\Service\Fee
 */
class Structure
{
    // This can also be fetched from the environment files
    const MIN_AMOUNT_ALLOWED = 1000;
    const MAX_AMOUNT_ALLOWED = 20000;

    /**
     * The Fee structure for different terms is defined here.
     * It maps the number of months in the term with the fee structure defined for the term.
     * The fee structure maps the principal amount value to the fee that should be charged for that amount.
     * All values are in £.
     *
     * @var array
     */
    public static $structure = [
        12 => [
            1000  => 50,
            2000  => 90,
            3000  => 90,
            4000  => 115,
            5000  => 100,
            6000  => 120,
            7000  => 140,
            8000  => 160,
            9000  => 180,
            10000 => 200,
            11000 => 220,
            12000 => 240,
            13000 => 260,
            14000 => 280,
            15000 => 300,
            16000 => 320,
            17000 => 340,
            18000 => 360,
            19000 => 380,
            20000 => 400,
        ],
        24 => [
            1000  => 70,
            2000  => 100,
            3000  => 120,
            4000  => 160,
            5000  => 200,
            6000  => 240,
            7000  => 280,
            8000  => 320,
            9000  => 360,
            10000 => 400,
            11000 => 440,
            12000 => 480,
            13000 => 520,
            14000 => 560,
            15000 => 600,
            16000 => 640,
            17000 => 680,
            18000 => 720,
            19000 => 760,
            20000 => 800,
        ],
    ];

    /**
     * @param int $term
     *
     * @return bool
     */
    public static function isValidTerm(int $term): bool
    {
        return (array_key_exists($term, self::$structure) === true);
    }

    /**
     * Returns the Fee structure for the given term
     *
     * @param int $term
     *
     * @return array
     */
    public static function getStructure(int $term): array
    {
        $termStructure = self::$structure[$term] ?? [];

        return $termStructure;
    }
}
