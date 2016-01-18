<?php

namespace Vector\Algebra\Lib;

use Vector\Util\FunctionCapsule;

abstract class Reader extends FunctionCapsule
{
    protected static function runReader($reader, $context)
    {
        return $reader->run($context);
    }

    protected static function ask()
    {
        return Reader::Reader(function($a) { return $a; });
    }
}