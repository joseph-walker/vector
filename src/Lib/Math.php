<?php

namespace Vector\Lib;

use Vector\Core\Module;

class Math extends Module
{
    /**
     * Arithmetic Addition
     *
     * Add two numbers together
     *
     * ```
     * $add(2, 2); // 4
     * $add(-1, 2); // 1
     * ```
     *
     * @type Num a => a -> a -> a
     *
     * @param  number $a First number to add
     * @param  number $b Second number to add
     * @return number    Addition of $a + $b
     */
    protected static function add($a, $b)
    {
        return $a + $b;
    }

    /**
     * Array Sum
     *
     * Add all the numbers of a list together and return their sum. If the given
     * list is empty, returns 0.
     *
     * ```
     * $sum([1, 2, 3]); // 6
     * $sum([]); // 0
     * ```
     *
     * @type Num a => [a] -> a
     *
     * @param  array  $a List of numbers to add
     * @return number    Sum of all the elements of the list
     */
    protected static function sum($a)
    {
        return array_reduce($a, function ($carry, $item) use ($a) {
            $carry += $item;
            return $carry;
        }, 0);
    }

    /**
     * Negate a number
     *
     * Returns a given number * -1
     *
     * ```
     * $negate(4); // -4
     * $negate(0); // 0
     * ```
     *
     * @type Num a => a -> a
     *
     * @param  number $a Number to make negative
     * @return number    The negated number
     */
    protected static function negate($a)
    {
        return -$a;
    }

    /**
     * Arithmetic Subtraction
     *
     * Subtracts two numbers, with the first argument being subtracted from the second.
     *
     * ```
     * $subtract(4, 9); // 5
     * $subtract(-1, 3); // 4
     * ```
     *
     * @type Num a => a -> a -> a
     *
     * @param  number $a Number to subtract
     * @param  number $b Number to subtract from
     * @return number    Subtraction of $b - $a
     */
    protected static function subtract($a, $b)
    {
        return $b - $a;
    }

    /**
     * Arithmetic Multiplication
     *
     * Multiply two numbers together
     *
     * ```
     * $multiply(2, 4); // 8
     * $multiply(0, 4); // 0
     * ```
     *
     * @type Num a => a -> a -> a
     *
     * @param  number $a First number to multiply
     * @param  number $b Second number to multiply
     * @return number    Multiplication of $a * $b
     */
    protected static function multiply($a, $b)
    {
        return $a * $b;
    }

    /**
     * Array Product
     *
     * Returns the product of a list of numbers, i.e. the result of multiplying
     * every element of a list together. Returns 1 for an empty list.
     *
     * ```
     * $product([2, 2, 3]); // 12
     * $product([]); // 1
     * ```
     *
     * @type Num a => [a] -> a
     *
     * @param  array $a List of values to multiply
     * @return mixed    Product of every value in the list
     */
    protected static function product($a)
    {
        return empty($a)
            ? 0
            : array_reduce($a, function ($carry, $item) use ($a) {
            $carry *= $item;
            return $carry;
        }, 1);
    }

    /**
     * Arithmetic Division
     *
     * Divide two numbers, with the first argument being the divisor
     *
     * ```
     * $divide(2, 8); // 4
     * $divide(4, 12); // 3
     * ```
     *
     * @type Num a => a -> a -> a
     *
     * @param  number $a Denominator
     * @param  number $b Numerator
     * @return float     Result of $b divided by $a
     */
    protected static function divide($a, $b)
    {
        return $b / $a;
    }

    /**
     * Modulus Operator
     *
     * Take the modulus of two integers, with the first argument being the divisor.
     * Returns the remainder of $b / $a.
     *
     * ```
     * $mod(2, 5); // 1
     * $mod(5, 12); // 2
     * $mod(3, 3); // 0
     * ```
     *
     * @type Int -> Int -> Int
     *
     * @param  int $a Divisor
     * @param  int $b Numerator
     * @return int    Remainder of $b / $a
     */
    protected static function mod($a, $b)
    {
        return $b % $a;
    }

    /**
     * Number Range
     *
     * Given two values m and n, return all values between m and n in an array, inclusive, with a
     * step size of $step. The list of numbers will start at the first value and approach the second value.
     *
     * ```
     * $range(1, 1, 5); // [1, 2, 3, 4, 5]
     * $range(2, 0, -3); // [0, -2]
     * $range(0, 0, 0); // [0]
     * $range(0.1, 0, 0.5); // [0, 0.1, 0.2, 0.3, 0.4, 0.5]
     * ```
     *
     * @type Num a => a -> a -> a
     *
     * @param  number $step The step sizes to take when building the range
     * @param  number $first    First value in the list
     * @param  number $last    Last value in the list
     * @return array        All the numbers between the first and last argument
     */
    protected static function range($step, $first, $last)
    {
        return range($first, $last, $step);
    }

    /**
     * Minimum Value
     *
     * Returns the minimum of two arguments a and b.
     * If a and be are equal, returns the first value. But since they're equal, that doesn't
     * really matter now does it?
     *
     * ```
     * $min(1, 2); // 1
     * $min(-1, -6); // -6
     * $min(5, 5); // 5
     * ```
     *
     * @type Num a => a -> a -> a
     *
     * @param  number $a First number to compare
     * @param  number $b Second number to compare
     * @return number    The lesser of the two numbers
     */
    protected static function min($a, $b)
    {
        return min([$a, $b]);
    }

    /**
     * Maximum Value
     *
     * Returns the maximum of two arguments a and b. If a and b are equal, just returns the value.
     *
     * ```
     * $max(1, 2); // 2
     * $max(-1, -6); // -1
     * $max(5, 5); // 5
     * ```
     *
     * @type Num a => a -> a -> a
     *
     * @param  number $a First number to compare
     * @param  number $b Second number to compare
     * @return number    The greater of the two numbers
     */
    protected static function max($a, $b)
    {
        return max([$a, $b]);
    }

    /**
     * Power function
     *
     * Arithmetic exponentiation. Raises the second argument to the power
     * of the first.
     *
     * ```
     * $pow(2, 3); // 3 ^ 2 = 9
     * $pow(3, 2); // 2 ^ 3 = 8
     * ```
     *
     * @type Num a => a -> a -> a
     *
     * @param  number $a The power exponent
     * @param  number $b The power base
     * @return number    The base raised to the exponent's power
     */
    protected static function pow($a, $b)
    {
        return pow($b, $a);
    }

    /**
     * Arithmetic mean
     *
     * Returns the average of a list, or zero for an empty list.
     *
     * ```
     * $mean([1, 2, 3]); // (1 + 2 + 3) / 3 = 2
     * $mean([]); // 0
     * ```
     *
     * @type Num a => [a] -> a
     *
     * @param  array  $arr List of numbers
     * @return number      Mean of input list
     */
    protected static function mean($arr)
    {
        return count($arr)
            ? array_sum($arr) / count($arr)
            : 0;
    }
}
