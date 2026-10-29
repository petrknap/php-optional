<?php

declare(strict_types=1);

namespace PetrKnap\Optional\JavaSe9;

use PetrKnap\Optional\JavaSe8;

/**
 * A container object which may or may not contain a non-null value.
 *
 * @template-covariant T of mixed type of non-null value
 *
 * @see https://docs.oracle.com/javase/9/docs/api/java/util/Optional.html
 *
 * @extends JavaSe8\Optional<T>
 *
 * @note This interface does not define `stream()`.
 */
interface Optional extends JavaSe8\Optional
{
    /**
     * If a value is present, performs the given action with the value, otherwise performs the given empty-based action.
     *
     * @param callable(T): mixed $action
     * @param callable(): mixed $emptyAction
     */
    public function ifPresentOrElse(callable $action, callable $emptyAction): void;

    /**
     * If a value is present, returns an {@see Optional} describing the value, otherwise returns an {@see Optional} produced by the supplying function.
     *
     * @template U of mixed type of non-null value
     * @template V of Optional<U>
     *
     * @param callable(): V $supplier
     *
     * @return V|Optional<U>
     */
    public function or(callable $supplier): Optional;
}
