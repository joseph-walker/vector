<?php

namespace Vector\Test\Lib;

use Vector\Core\Exception\EmptyListException;
use Vector\Core\Exception\IndexOutOfBoundsException;

use Vector\Lib\ArrayList;
use Vector\Data\Maybe;

class ArrayListTest extends \PHPUnit_Framework_TestCase
{
    protected $testCase;

    protected function setUp()
    {
        $this->testCase = [0, 1, 2, 3];
    }

    /**
     * Tests that head returns the first element of a list
     */
    public function testHeadReturnsFirstElement()
    {
        $head = ArrayList::Using('head');

        $this->assertEquals($head($this->testCase), 0);
    }

    /**
     * Expect that an EmptyListException is thrown for head on empty lists
     */
    public function testHeadUndefinedOnEmptyList()
    {
        $head = ArrayList::Using('head');
        $this->expectException(EmptyListException::class);

        $head([]); // Throws Exception
    }

    /**
     * Test that tail returns the rest of a list sans first element
     */
    public function testTailReturnsAllButFirstElement()
    {
        $tail = ArrayList::Using('tail');

        $this->assertEquals($tail($this->testCase), [1, 2, 3]);
    }

    /**
     * Test that init returns the first chunk of an array
     */
    public function testInitReturnsAllButLastElement()
    {
        $init = ArrayList::Using('init');

        $this->assertEquals($init($this->testCase), [0, 1, 2]);
    }

    /**
     * Test that last returns the last element of a list
     */
    public function testLastReturnsLastElement()
    {
        $last = ArrayList::Using('last');

        $this->assertEquals($last($this->testCase), 3);
    }

    /**
     * Expect that an EmptyListException is thrown for last on empty lists
     */
    public function testLastUndefinedOnEmptyList()
    {
        $last = ArrayList::Using('last');
        $this->expectException(EmptyListException::class);

        $last([]); // Throws Exception
    }

    /**
     * Test that length returns the length of a list
     */
    public function testLengthReturnsLength()
    {
        $length = ArrayList::Using('length');

        $this->assertEquals($length($this->testCase), 4);
    }

    /**
     * Test that index returns the element of a list at the given index
     */
    public function testIndexReturnsElementAtIndex()
    {
        $index = ArrayList::Using('index');

        $this->assertEquals($index(2, $this->testCase), 2);
    }

    /**
     * Test that index throws an exception when requesting a non-existant index
     */
    public function testIndexThrowsExceptionForNoKey()
    {
        $index = ArrayList::Using('index');
        $this->expectException(IndexOutOfBoundsException::class);

        $index(17, [1, 2, 3]);
    }

    /**
     * Tests that maybeIndex returns maybe values
     */
    public function testMaybeIndexReturnsMaybeValues()
    {
        $maybeIndex = ArrayList::Using('maybeIndex');

        $this->assertEquals(Maybe::Just(2), $maybeIndex(2, $this->testCase));
        $this->assertEquals(Maybe::Nothing(), $maybeIndex(17, $this->testCase));
    }

    /**
     * Test that concat handles normal arrays, and key/value arrays properly
     */
    public function testConcatAppendsTwoArrays()
    {
        $concat = ArrayList::Using('concat');

        $this->assertEquals([0, 1, 2, 3, 0, 1, 2, 3], $concat($this->testCase, $this->testCase));
        $this->assertEquals(['foo' => 1, 'bar' => 2], $concat(['foo' => 1], ['bar' => 2]));
        $this->assertEquals(['foo' => 'baz', 'bar' => 2], $concat(['foo' => 1, 'bar' => 2], ['foo' => 'baz']));
    }

    /**
     * Test that set properly sets the value of an array at the index in an
     * immutable way
     */
    public function testSetSetsArrayValueAtIndex()
    {
        $set = ArrayList::Using('set');

        $this->assertEquals($set(2, $this->testCase, 0), [0, 1, 0, 3]);
        $this->assertEquals($this->testCase, [0, 1, 2, 3]);
    }

    /**
     * Test that keys returns the keys of a key/value array
     */
    public function testKeysReturnsMapKeys()
    {
        $keys = ArrayList::using('keys');

        $this->assertEquals([0, 1, 2], $keys([5, 5, 5]));
        $this->assertEquals(['foo', 'bar', 'baz'], $keys(['foo' => 1, 'bar' => 2, 'baz' => 3]));
    }

    /**
     * Test that values returns key/value array values
     */
    public function testValuesReturnsMapValues()
    {
        $values = ArrayList::using('values');

        $this->assertEquals([1, 2, 3], $values([1, 2, 3]));
        $this->assertEquals([1, 2, 3], $values(['foo' => 1, 'bar' => 2, 'baz' => 3]));
    }

    /**
     * Test that the filter function correctly filters an array of data
     */
    public function testFilterFunctionFiltersArrays()
    {
        $filter = ArrayList::using('filter');

        $id = function($a) { return true; };
        $gt = function($b) { return $b >= 2; };

        $this->assertEquals([0, 1, 2, 3], $filter($id, $this->testCase));
        $this->assertEquals([2 => 2, 3 => 3], $filter($gt, $this->testCase));
    }

    /**
     * Test that the zipWith function works properly, specifically the cases
     * where it is given arrays of unequal length
     */
    public function testZipWithCombinesArraysProperly()
    {
        $zipWith = ArrayList::using('zipWith');

        $combinator = function($a, $b) { return $a + $b; };

        $this->assertEquals([1, 2, 3], $zipWith($combinator, [5, 5, 5], [-4, -3, -2]));
        $this->assertequals([0], $zipWith($combinator, [5, 5, 5], [-5]));
        $this->assertequals([5], $zipWith($combinator, [5], [0, 5, 5]));
        $this->assertEquals([], $zipWith($combinator, [], [1, 2, 3]));

        // Test that it ignore keys
        $this->assertEquals([2, 4], $zipWith($combinator, ['foo' => 1, 'bar' => 2], [1 => 1, 5 => 2]));
    }

    /**
     * Tests foldl by reducing an array with an add function
     */
    public function testFoldLReducesAnArray()
    {
        $foldl = ArrayList::using('foldl');

        $reducer = function($a, $b) { return $a + $b; };

        $this->assertEquals(6, $foldl($reducer, 0, [1, 2, 3]));
    }

    /**
     * Tests that the drop function returns arrays without the leading elements
     */
    public function testDropReturnsModifiedArrays()
    {
        $drop = ArrayList::using('drop');

        $this->assertequals([1, 2, 3], $drop(3, [0, 0, 0, 1, 2, 3]));
        $this->assertequals([1, 2], $drop(0, [1, 2]));
        $this->assertequals([], $drop(5, [1, 2, 3]));
    }
}
