<?php

namespace Vector\Test\Control;

use Vector\Control\Applicative;
use Vector\Control\Functor;
use Vector\Lib\Math;

class ApplicativeTst extends \PHPUnit_Framework_TestCase
{
    /**
     * Apply should operator on arrays as well as applicative instances
     * The array instance is effectively a vector cross product
     */
    public function testApply_arrayArgument()
    {
        $ap   = Applicative::using('apply');
        $fmap = Functor::using('fmap');
        $add  = Math::using('add');
        $mult = Math::using('multiply');

        $this->assertEquals($ap($fmap($add, [1, 2, 3]), [1]), [2, 3, 4]);
        $this->assertEquals($ap($fmap($mult, [1, 2, 3]), [1, 2, 3]), [1, 2, 3, 2, 4, 6, 3, 6, 9]);
    }

    /**
     * LiftA2 calls off to pure and apply for array arguments - test
     * that it hands arrays off properly
     */
    public function testLiftA2_arrayArgument()
    {
        $liftA2 = Applicative::using('liftA2');
        $add    = Math::using('add');

        $this->assertEquals($liftA2([], $add, [1, 2, 3], [1, 2, 3]), [2, 3, 4, 3, 4, 5, 4, 5, 6]);
    }
}
