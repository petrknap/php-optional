<?php

declare(strict_types=1);

namespace PetrKnap\Optional\JavaSe8;

use Throwable;

/**
 * A container object which may or may not contain a non-null value.
 *
 * @template-covariant T of mixed type of non-null value
 *
 * @see https://docs.oracle.com/javase/8/docs/api/java/util/Optional.html
 */
interface Optional
{
    /**
     * Returns an empty {@see Optional} instance.
     *
     * @return self<T>
     */
    public static function empty(): self;

    /**
     * Returns an {@see Optional} with the specified present non-null value.
     *
     * @template U of T
     *
     * @param U $value
     *
     * @return self<U>
     */
    public static function of(mixed $value): self;

    /**
     * Returns an {@see Optional} describing the specified value, if non-null, otherwise returns an empty {@see Optional}.
     *
     * @template U of T
     *
     * @param U|null $value
     *
     * @return self<U>
     */
    public static function ofNullable(mixed $value): self;

    /**
     * Indicates whether some other object is "equal to" this {@see Optional}.
     */
    public function equals(mixed $obj): bool;

    /**
     * If a value is present, and the value matches the given predicate, return an {@see Optional} describing the value, otherwise return an empty {@see Optional}.
     *
     * @param callable(T): bool $predicate
     *
     * @return self<T>
     */
    public function filter(callable $predicate): self;

    /**
     * If a value is present, apply the provided {@see Optional}-bearing mapping function to it, return that result, otherwise return an empty {@see Optional}.
     *
     * @template U of mixed type of non-null value
     * @template V of Optional<U>
     *
     * @param callable(T): V $mapper
     *
     * @return V|Optional<U>
     */
    public function flatMap(callable $mapper): Optional;

    /**
     * If a value is present in this {@see Optional}, returns the value, otherwise throws {@see NoSuchElementException}.
     *
     * @return T
     *
     * @throws NoSuchElementException
     */
    public function get(): mixed;

    /**
     * If a value is present, invoke the specified consumer with the value, otherwise do nothing.
     *
     * @param callable(T): mixed $consumer
     */
    public function ifPresent(callable $consumer): void;

    /**
     * Return `true` if there is a value present, otherwise `false`.
     */
    public function isPresent(): bool;

    /**
     * If a value is present, apply the provided mapping function to it, and if the result is non-null, return an {@see Optional} describing the result.
     *
     * @template U of mixed type of non-null value
     *
     * @param callable(T): U $mapper
     *
     * @return self<U>
     */
    public function map(callable $mapper): self;

    /**
     * Return the value if present, otherwise return other.
     *
     * @template U of T
     *
     * @param U|null $other
     *
     * @return ($other is U ? T : T|null)
     */
    public function orElse(mixed $other): mixed;

    /**
     * Return the value if present, otherwise invoke provided supplier and return the result of that invocation.
     *
     * @template U of T
     *
     * @param (callable(): U)|(callable(): (U|null)) $otherSupplier
     *
     * @return ($otherSupplier is (callable(): U) ? T : T|null)
     */
    public function orElseGet(callable $otherSupplier): mixed;

    /**
     * Return the contained value, if present, otherwise throw an exception to be created by the provided supplier.
     *
     * @template E of Throwable
     *
     * @param callable(): E $exceptionSupplier
     *
     * @return T
     *
     * @throws E
     */
    public function orElseThrow(callable $exceptionSupplier): mixed;
}
