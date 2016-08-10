<?php

namespace Vector\Lib;

use Vector\Core\Exception\EmptyListException;
use Vector\Core\Exception\IndexOutOfBoundsException;

use Vector\Core\Module;
use Vector\Control\Functor;

/**
 * @method static array groupBy(callable $keyGen, array $list) Return a grouping of $list elements grouped by the $keyGen function.
 * @method static array cons() cons($a, array $arr) Return $arr with $a appended to the end.
 * @method static mixed head() head(array $list) Return the first element of a list.
 * @method static array map() map(Callable $f, array $list) Return a transformed list.
 * @method static array mapIndexed() mapIndexed(Callable $f, array $list) Return a transformed list, receives indexes.
 * @method static tail($list) Return a list sans first element.
 * @method static init($list) Return a list sans last element.
 * @method static last($list) Return the last element of a list.
 * @method static length($list) Return the length of a list.
 * @method static index($i, $list) Return the element of a list at index 'i'.
 * @method static filter($f, $arr) Return a filtered array with every element passing test 'f'.
 * @method static keys($arr) Return the keys of a list.
 * @method static values($arr) Return the values of a list.
 * @method static concat($a, $b) Return two lists concatenated together.
 * @method static setValue($key, $arr, $val) Return a list with the element at index 'key' set to 'val'.
 * @method static foldl($f, $seed, $list) Return the result of folding 'f' over a list with initial value 'seed', from the left.
 * @method static zipWith($f, $a, $b) Return two lists combined by combinator 'f'.
 * @method static drop($n, $list) Return a list with 'n' elements removed from the front.
 * @method static dropWhile($predicate, $list) Return a list with elements removed so long as they pass 'predicate'.
 * @method static take($n, $list) Return the first 'n' elements of a list.
 * @method static takeWhile($predicate, $list) Return the first elements of a list that all pass 'predicate'.
 * @method static reverse($list) Return a list in the reverse order.
 * @method static flatten($list) Return a flattened list.
 * @method static contains($item, $list) Return whether or not a list contains 'item'.
 * @method static replicate($n, $item) Return 'item' repeated 'n' times into a list.
 * @method static unique($list) Return unique values as list.
 * @method static sort($comp, $list) Given a function that compares two values, sort an array.
 */
class ArrayList extends Module
{
    /**
     * Cons Operator
     *
     * Given a value and an array, append that value to the end of the array.
     *
     * @example
     * ArrayList::cons(3, [1, 2]); // [1, 2, 3]
     *
     * @example
     * ArrayList::cons(1, []); // [1]
     *
     * @type a -> [a] -> [a]
     *
     * @param  mixed $a   Value to add to array
     * @param  array $arr Array to add value to
     * @return array      Array with value added
     */
    protected static function __cons($a, $arr)
    {
        $arr[] = $a;
        return $arr;
    }

    /**
     * Group By
     *
     * Given a function that turns an element into a string, map over a list of elements
     * and return a multi-dimensional array with elements grouped together by their key
     * generator.
     *
     * @example
     * $testCase = [1, 2, 3, 4, 5, 6, 7];
     * $keyGen = function($a) {
     *     return ($a <= 3) ? 'small' : 'big';
     * };
     * ArrayList::groupBy($keyGen, $testCase); // ['small' => [1, 2, 3], 'big' => [4, 5, 6, 7]]
     *
     * @type (a -> String) -> [a] -> [[a]]
     *
     * @param  \Closure $keyGen Key generating function
     * @param  array    $list   List to group
     * @return array            Multidimensional array of grouped elements
     */
    protected static function __groupBy($keyGen, $list)
    {
        return self::foldl(function($group, $element) use ($keyGen) {
            $group[$keyGen($element)][] = $element;
            return $group;
        }, [], $list);
    }

    /**
     * List Head
     *
     * Returns the first element of a list, the element at index 0. Also functions
     * properly for key/value arrays, e.g. arrays whose first element may not necessarily
     * be index 0. If an empty array is given, head throws an Exception.
     *
     * @example
     * ArrayList::head([1, 2, 3]); // 1
     *
     * @example
     * ArrayList::head(['a' => 1, 'b' => 2]); // 1
     *
     * @example
     * ArrayList::head([]); // Exception thrown
     *
     * @type [a] -> a
     *
     * @throws \Vector\Core\Exception\EmptyListException if argument is empty list
     *
     * @param  array $list Key/Value array or List
     * @return Mixed       First element of $list
     */
    protected static function __head($list)
    {
        if (count($list) === 0)
            throw new EmptyListException("'head' function is undefined for empty lists.");

        return reset($list);
    }

    /**
     * Array Map
     *
     * Given some function and a list of arbitrary length, return a new array that is the
     * result of calling the given function on each element of the original list.
     *
     * @example
     * ArrayList::map($add(1), [1, 2, 3]); // [2, 3, 4]
     *
     * @type (a -> b) -> [a] -> [b]
     *
     * @param  callable $f    Function to call for each element
     * @param  array    $list List to call function on
     * @return array          New list of elements after calling $f for the original list elements
     */
    protected static function __map($f, $list)
    {
        $map = Functor::using('fmap');
        return $map($f, $list);
    }

    /**
     * Array Map Indexed
     *
     * Given some function and a list of arbitrary length, return a new array that is the
     * result of calling the given function on each element of the original list. The first argument
     * of the mapping function is the value, and the second argument is the key or index of the array being
     * mapped over.
     *
     * @example
     * ArrayList::mapIndexed($filterEvenIndexes, [1, 2, 3]); // [null, 2, null]
     *
     * @type (a -> b -> c) -> [a] -> [c]
     *
     * @param  callable $f    Function to call for each element
     * @param  array    $list List to call function on
     * @return array          New list of elements after calling $f for the original list elements
     */
    protected static function __mapIndexed($f, $list)
    {
        return array_map($f, $list, array_keys($list));
    }

    /**
     * Array Sort
     *
     * Given a function that compares two values, sort an array. This function defers to usort
     * but does not mutate the original array. The comparison function should return -1 if the
     * first argument is ordered before the second, 0 if it's the same ordering, and 1 if
     * first argument is ordered after the second.
     *
     * @example
     * $comp = function($a, $b) { return $a <=> $b; };
     * ArrayList::sort($comp, [3, 2, 1]);
     *
     * @type (a -> a -> Int) -> [a] -> [a]
     *
     * @param  callable $comp The comparison function
     * @param  array    $list The list to sort
     * @return array          The sorted list
     */
    protected static function __sort($comp, $list)
    {
        usort($list, $comp);

        return $list;
    }

    /**
     * List Tail
     *
     * Returns an array without its first element, e.g. the complement of `head`. Works on
     * key/value arrays as well as 'regular' arrays. If an empty array of an array of one element
     * is given, returns an empty array.
     *
     * @example
     * ArrayList::([1, 2, 3]); // [2, 3]
     *
     * @example
     * ArrayList::(['a' => 1, 'b' => 2]); // ['b' => 2];
     *
     * @type [a] -> [a]
     *
     * @param  array $list Key/Value array or List
     * @return array       $list without the first element
     */
    protected static function __tail($list)
    {
        return array_slice($list, 1, count($list));
    }

    /**
     * Initial List Values
     *
     * Returns an array without its last element, e.g. the inverse of `tail`. Works on
     * key/value arrays as well as 'regular' arrays. If an empty or single-value array is given,
     * returns an empty array.
     *
     * @example
     * ArrayList::init([1, 2, 3]); // [1, 2]
     *
     * @example
     * ArrayList::init(['a' => 1, 'b' => 2]); // ['a' => 1];
     *
     * @type [a] -> [a]
     *
     * @param  array $list Key/Value array or List
     * @return array       $list without the last element
     */
    protected static function __init($list)
    {
        return array_slice($list, 0, count($list) - 1);
    }

    /**
     * Last List Value
     *
     * Returns the last element of an array, e.g. the complement of `init`. Works on key/value
     * arrays as well as 'regular' arrays. If an empty array is given, throws an exception.
     *
     * @example
     * ArrayList::last([1, 2, 3]); // 3
     *
     * @example
     * ArrayList::last(['a' => 1, 'b' => 2]); // 2
     *
     * @example
     * ArrayList::last([]); // Exception thrown
     *
     * @type [a] -> a
     *
     * @throws \Vector\Core\Exception\EmptyListException if argument is empty list
     *
     * @param  array $list Key/Value array or List
     * @return Mixed       The last element of $list
     */
    protected static function __last($list)
    {
        if (count($list) === 0)
            throw new EmptyListException("'last' function is undefined for empty lists.");

        return array_slice($list, -1, 1)[0];
    }

    /**
     * Array Length
     *
     * Returns the length of a list or array. Wraps php `count` function.
     *
     * @example
     * ArrayList::length([1, 2, 3]); // 3
     *
     * @example
     * ArrayList::length(['a' => 1, 'b' => 2]); // 2
     *
     * @type [a] -> a
     *
     * @param  array $list Key/Value array or List
     * @return Int         Length of $list
     */
    protected static function __length($list)
    {
        return count($list);
    }

    /**
     * List Index
     *
     * Returns the element of a list at the given index. Throws an exception
     * if the given index does not exist in the list.
     *
     * @example
     * ArrayList::index(0, [1, 2, 3]); // 1
     *
     * @example
     * ArrayList::index('foo', ['bar' => 1, 'foo' => 2]); // 2
     *
     * @example
     * ArrayList::index('baz', [1, 2, 3]); // Exception thrown
     *
     * @type Int -> [a] -> a
     *
     * @throws \Vector\Core\Exception\IndexOutOfBoundsException if the requested index does not exist
     *
     * @param  Int   $i    Index to get
     * @param  array $list List to get index from
     * @return Mixed       Item from $list and index $i
     */
    protected static function __index($i, $list)
    {
        /**
         * isset is much faster at the common case (non-null values)
         * but it falls down when the value is null, so we fallback to
         * array_key_exists (slower).
         */
        if (!isset($list[$i]) && !array_key_exists($i, $list)){
            throw new IndexOutOfBoundsException("'index' function tried to access non-existent index '$i'");
        }

        return $list[$i];
    }

    /**
     * Filter a List
     *
     * Returns a filtered list. Given a function that takes an element and returns
     * either true or false, return a list of all the elements
     * of the input list that pass the test.
     *
     * @note 'filter' preserves the keys of a key/value array - it only looks at values
     *
     * @example
     * ArrayList::filter(function($a) { return $a > 2; }, [1, 2, 3, 4, 5]); // [3, 4, 5], using an inline function
     *
     * @example
     * ArrayList::filter(function($a) { return $a > 2; }, ['foo' => 1, 'bar' => 3]); // ['foo' => 1]
     *
     * @example
     * ArrayList::filter(Math::lte(2), [1, 2, 3, 4, 5]); // [1, 2], using `lte` from the Math module
     *
     * @type (a -> Bool) -> [a] -> [a]
     *
     * @param  Callable $f   Test function - should take an `a` and return a Bool
     * @param  array    $arr List to filter
     * @return array         Result of filtering the list
     */
    protected static function __filter($f, $arr)
    {
        return array_filter($arr, $f);
    }

    /**
     * Array Keys
     *
     * Returns the keys of an associative key/value array. Returns numerical indexes
     * for non key/value arrays.
     *
     * @example
     * ArrayList::keys(['a' => 1, 'b' => 2]); // ['a', 'b']
     *
     * @example
     * ArrayList::keys([1, 2, 3]); // [0, 1, 2]
     *
     * @type [a] -> [b]
     *
     * @param  array $arr List to get keys from
     * @return array      The keys of $arr
     */
    protected static function __keys($arr)
    {
        return array_keys($arr);
    }

    /**
     * Array Values
     *
     * Returns the values of an associative key/value array.
     *
     * @example
     * ArrayList::values(['a' => 1, 'b' => 2]); // [1, 2]
     *
     * @example
     * ArrayList::values([1, 2, 3]); // [1, 2, 3]
     *
     * @type [a] -> [a]
     *
     * @param  array $arr Key/Value array
     * @return array      Indexed array with values of $arr
     */
    protected static function __values($arr)
    {
        return array_values($arr);
    }

    /**
     * Array Concatenation
     *
     * Joins two arrays together, with the second argument being appended
     * to the end of the first. Defers to php build-in function `array_merge`,
     * so repeated keys will be overwritten.
     *
     * @example
     * ArrayList::concat([1, 2], [2, 3]); // [1, 2, 2, 3]
     *
     * @example
     * ArrayList::concat(['a' => 1, 'b' => 2], ['a' => 'foo', 'c' => 3]); // ['a' => 'foo', 'b' => 2, 'c' => 3]
     *
     * @type [a] -> [a] -> [a]
     *
     * @param  array $a List to be appended to
     * @param  array $b List to append
     * @return array    Concatenated list of $a and $b
     */
    protected static function __concat($a, $b)
    {
        return array_merge($a, $b);
    }

    /**
     * Set Array Value
     *
     * Sets the value of an array at the given index; works for non-numerical indexes.
     * The value is set in an immutable way, so the original array is not modified.
     *
     * @example
     * ArrayList::setValue(0, 'foo', [1, 2, 3]); // ['foo', 2, 3]
     *
     * @example
     * ArrayList::setValue('c', 3, ['a' => 1, 'b' => 2]); // ['a' => 1, 'b' => 2, 'c' => 3]
     *
     * @type a -> b -> [b] -> [b]
     *
     * @param  Mixed $key Element of index to modify
     * @param  Mixed $val Value to set $arr[$key] to
     * @param  array $arr Array to modify
     * @return array      Result of setting $arr[$key] = $val
     */
    protected static function __setIndex($key, $val, $arr)
    {
        $arr[$key] = $val;
        return $arr;
    }

    /**
     * List Fold - From Left
     *
     * Fold a list by iterating over the list from left to right. Pass each element, one by one, into
     * the fold function $f, and carry its value over to the next iteration. Also referred to as array
     * reduce.
     *
     * @example
     * $add = function($a, $b) { return $a + $b; };
     * ArrayList::foldl(Math::add(), 0, [1, 2, 3]); // 6
     *
     * @example
     * ArrayList::foldl(Logic::and(), True, [True, True]); // True
     *
     * @example
     * ArrayList::foldl(Logic::and(), True, [True, True, False]); // False
     *
     * @type (b -> a -> b) -> b -> [a] -> b
     *
     * @param  callable $f    Function to use in each iteration if the fold
     * @param  mixed    $seed The initial value to use in the  fold function along with the first element
     * @param  array    $list The list to fold over
     * @return mixed          The result of applying the fold function to each element one by one
     */
    protected static function __foldl($f, $seed, $list)
    {
        return array_reduce($list, $f, $seed);
    }

    /**
     * Custom Array Zip
     *
     * Given two arrays a and b, and some combinator f, combine the arrays using the combinator
     * f(ai, bi) into a new array c. If a and b are not the same length, the resultant array will
     * always be the same length as the shorter array, i.e. the zip stops when it runs out of pairs.
     *
     * @example
     * $combinator = function($a, $b) { return $a + $b; };
     * ArrayList::zipWith($combinator, [1, 2, 3], [0, 8, -1]); // [1, 10, 2]
     *
     * @example
     * $combinator = function($a, $b) { return $a - $b; };
     * ArrayList::zipWith($combinator, [0], [1, 2, 3]); // [-1]
     *
     * @type (a -> b -> c) -> [a] -> [b] -> [c]
     *
     * @param  Callable $f The function used to combine $a and $b
     * @param  array    $a The first array to use in the combinator
     * @param  array    $b The second array to use in the combinator
     * @return array       The result of calling f with each element of a and b in series
     */
    protected static function __zipWith($f, $a, $b)
    {
        $result = [];

        while (($ai = array_shift($a)) !== null && ($bi = array_shift($b)) !== null) {
            $result[] = $f($ai, $bi);
        }

        return $result;
    }

    /**
     * Array Zip
     *
     * Given two arrays a and b, return a new array where each element is a tuple of a and b. If a and b
     * are not the same length, the resultant array will always be the same length as the shorter array.
     *
     * @example
     * ArrayList::zip([1, 2, 3], ['a', 'b', 'c']); // [[1, 'a'], [2, 'b'], [3, 'c']]
     *
     * @type [a] -> [b] -> [(a, b)]
     *
     * @param  array $a The first array to use when zipping
     * @param  array $b The second array to use when zipping
     * @return array    Array of tuples from a and b combined
     */
    protected static function __zip($a, $b)
    {
        return self::zipWith(function($a, $b) { return [$a, $b]; }, $a, $b);
    }

    /**
     * Array Bifurcation
     *
     * Given an array and some filtering test that returns a boolean, return two arrays - one array
     * of elements that pass the test, and another array of elements that don't. Similar to filter,
     * but returns the elements that fail as well.
     *
     * @example
     * ArrayList::bifurcate($isEven, [1, 2, 3, 4, 5]); // [[2, 4], [1, 3, 5]]
     *
     * @type (a -> Bool) -> [a] -> ([a], [a])
     *
     * @param  callable $test Test to use when bifurcating the array
     * @param  array    $arr  Array to split apart
     * @return array          An array with two elements; the first is the list that passed the test,
     *                        and the second element is the list that failed the test
     */
    protected static function __bifurcate($test, $arr)
    {
        $resPass = [];
        $resFail = [];

        foreach ($arr as $element) {
            if ($test($element))
                $resPass[] = $element;
            else
                $resFail[] = $element;
        }

        return [$resPass, $resFail];
    }

    /**
     * Drop Elements
     *
     * Given some number n, drop n elements from an input array and return the rest of
     * the elements. If n is greater than the length of the array, returns an empty array.
     *
     * @example
     * ArrayList::drop(2, [1, 2, 3, 4]); // [3, 4]
     *
     * @example
     * ArrayList::drop(4, [1, 2]); // []
     *
     * @type Int -> [a] -> [a]
     *
     * @param  Int   $n    The number of elements to drop
     * @param  array $list List to drop elements from
     * @return array       Original list minus n elements from the front
     */
    protected static function __drop($n, $list)
    {
        return array_slice($list, $n, count($list));
    }

    /**
     * Drop Elements with Predicate
     *
     * Given some function that returns true or false, drop elements from an array starting
     * at the front, testing each element along the way, until that function returns false.
     * Return the array without all of those elements.
     *
     * @example
     * $greaterThanOne = function($n) { return $n > 1; };
     * ArrayList::dropWhile($greaterThanOne, [2, 4, 6, 1, 2, 3]); // [1, 2, 3]
     *
     * @type (a -> Bool) -> [a] -> [a]
     *
     * @param  callable $predicate Function to use for testing
     * @param  array    $list      List to drop from
     * @return array               List with elements removed from the front
     */
    protected static function __dropWhile($predicate, $list)
    {
        foreach ($list as $item) {
            if ($predicate($item)) {
                array_shift($list);
            }
            else {
                break;
            }
        }

        return $list;
    }

    /**
     * Take Elements
     *
     * Given some number n, return the first n elements of a given array. Returns the whole
     * array if n is greater than the array length.
     *
     * @example
     * ArrayList::take(3, [1, 2, 3, 4, 5]); // [1, 2, 3]
     *
     * @type Int -> [a] -> [a]
     *
     * @param  int   $n    Number of elements to take
     * @param  array $list Array to take elements from
     * @return array       First n elements of the array
     */
    protected static function __take($n, $list)
    {
        return array_slice($list, 0, $n);
    }

    /**
     * Take Elements with Predicate
     *
     * Given some function that returns true or false, return the first elements of the array
     * that all pass the test, until the test fails.
     *
     * @example
     * $greaterThanOne = function($n) { return $n > 1; };
     * ArrayList::takeWhile($greaterThanOne, [5, 5, 5, 1, 5, 5]); // [5, 5, 5]
     *
     * @type (a -> Bool) -> [a] -> [a]
     *
     * @param  callable $predicate Function to use for testing each element
     * @param  array    $list      List to take elements from
     * @return array               First elements of list that all pass the $predicate
     */
    protected static function __takeWhile($predicate, $list)
    {
        $result = [];

        foreach ($list as $item) {
            if ($predicate($item)) {
                $result[] = $item;
            }
            else {
                break;
            }
        }

        return $result;
    }

    /**
     * Array Reverse
     *
     * Flip the order of a given array. Does not modify the original array.
     *
     * @example
     * ArrayList::reverse([1, 2, 3]); // [3, 2, 1]
     *
     * @type [a] -> [a]
     *
     * @param  array $list Array to flip
     * @return array       Array in the reverse order
     */
    protected static function __reverse($list)
    {
        return array_reverse($list);
    }

    /**
     * Array Flatten
     *
     * Flattens a nested array structure into a single-dimensional array. Can handle
     * arrays of arbitrary dimension.
     *
     * @example
     * ArrayList::flatten([1, [2], [[[3, 4, [5]]]]]); // [1, 2, 3, 4, 5]
     *
     * @type [a] -> [b]
     *
     * @param  array $list Nested array to flatten
     * @return array       Result of flattening $list into a 1-dimensional list
     */
    protected static function __flatten($list)
    {
        $iter = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($list));
        $flat = [];

        foreach ($iter as $item) {
            $flat[] = $item;
        }

        return $flat;
    }

    /**
     * Array Contains Element
     *
     * Returns true if a given array contains the item to test, or false if
     * it does not.
     *
     * @example
     * ArrayList::contains(1, [1, 2, 3]); // true
     *
     * @example
     * ArrayList::contains('a', ['b', 'c', 'd']); // false
     *
     * @type a -> [a] -> Bool
     *
     * @param  mixed $item Item to test for
     * @param  array $list Array to test for the existence of $item in
     * @return bool        Whether or not $item is in $list
     */
    protected static function __contains($item, $list)
    {
        return in_array($item, $list);
    }

    /**
     * Replicate Item
     *
     * Given some integer n and an item to repeat, repeat that item and place
     * the results into an array of length n.
     *
     * @example
     * ArrayList::replicate(5, 'foo'); // ['foo', 'foo', 'foo', 'foo', 'foo']
     *
     * @type Int -> a -> [a]
     *
     * @param  int   $n    Times to repeat some item
     * @param  mixed $item Item to repeat
     * @return array       Array with $n items
     */
    protected static function __replicate($n, $item)
    {
        $result = [];

        for ($i = 0; $i < $n; $i++) {
            $result[] = $item;
        }

        return $result;
    }

    /**
     * Unique
     *
     * Given a list, return only unique values
     *
     * @example
     * ArrayList::unique([1, 2, 2, 4]); // [1, 2, 4]
     *
     * @type [a] -> [a]
     *
     * @param  array $list List of items to make unique
     * @return array Original list minus duplicates
     */
    protected static function __unique($list)
    {
        return array_values(array_flip(array_flip($list)));
    }
}
