<?php

declare(strict_types=1);

namespace PetrKnap\Optional\JavaSe10;

use PetrKnap\Optional\JavaSe8;
use PetrKnap\Optional\JavaSe9;
use Throwable;

/**
 * A container object which may or may not contain a non-null value.
 *
 * @template-covariant T of mixed type of non-null value
 *
 * @see https://docs.oracle.com/javase/10/docs/api/java/util/Optional.html
 *
 * @extends JavaSe9\Optional<T>
 */
interface Optional extends JavaSe9\Optional
{
    /**
     * @template E of Throwable
     *
     * @param null|callable(): E $exceptionSupplier
     *
     * @return T
     *
     * @throws ($exceptionSupplier is null ? JavaSe8\NoSuchElementException : E)
     */
    public function orElseThrow(?callable $exceptionSupplier = null): mixed;
}
