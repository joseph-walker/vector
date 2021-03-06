<?php

namespace Vector\Test\Control\Stub;

use Vector\Control\Extractable;

class TestExtractableObject implements Extractable
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function extract()
    {
        return $this->value;
    }
}
