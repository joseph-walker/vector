<?php

namespace Vector\Typeclass;

/**
 * Trait Extractable
 */
trait IsFunctor
{
    /**
     * @param callable $f
     * @return static
     */
    public function fmap(callable $f)
    {
        $args = $this->extract();

        if (empty($args) || !is_array($args)) {
            return new static;
        } else {
            return new static($f(...$this->extract()));
        }
    }

    /**
     * @return array|static
     */
    public function extract()
    {
        $constructedValues = array_values(get_object_vars($this));

        if (empty($constructedValues)) {
            return new static;
        } else {
            return array_values(get_object_vars($this));
        }
    }
}
