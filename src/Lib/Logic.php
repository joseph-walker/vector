<?php

namespace Vector\Lib;

use Vector\Core\FunctionCapsule;

class Logic extends FunctionCapsule
{
    // (a -> Bool) -> (a -> Bool) -> a -> Bool
    protected static function logicalOr($f, $g)
    {
        return function ($x) use ($f, $g) {
            return $f($x) || $g($x);
        };
    }
}