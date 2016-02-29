<?php

namespace Vector\Lib;

use Vector\Core\Module;

abstract class Lambda extends Module
{
    protected static function pipe(...$fs)
    {
        return function(...$args) use ($fs) {
            $carry = null;

            foreach ($fs as $f) {
                $carry = $carry
                    ? $f($carry)
                    : $f(...$args);
            }

            return $carry;
        };
    }

    protected static function compose(...$fs)
    {
        return self::pipe(...array_reverse($fs));
    }

    protected static function k($a)
    {
        return function(...$null) use ($a)
        {
            return $a;
        };
    }

    protected static function id($a)
    {
        return $a;
    }
}