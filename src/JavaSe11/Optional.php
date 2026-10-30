<?php

declare(strict_types=1);

namespace PetrKnap\Optional\JavaSe11;

use PetrKnap\Optional\JavaSe10;

/**
 * A container object which may or may not contain a non-null value.
 *
 * @template-covariant T of mixed type of non-null value
 *
 * @see https://docs.oracle.com/en/java/javase/11/docs/api/java.base/java/util/Optional.html
 *
 * @extends JavaSe10\Optional<T>
 */
interface Optional extends JavaSe10\Optional
{
    /**
     * If a value is not present, returns `true`, otherwise `false`.
     */
    public function isEmpty(): bool;
}
