<?php

declare(strict_types=1);

namespace PetrKnap\Optional\JavaSe8;

use Throwable;

/**
 * A container object which may or may not contain a non-null value.
 *
 * @template T of mixed type of non-null value
 *
 * @see https://docs.oracle.com/javase/8/docs/api/java/util/Optional.html
 */
interface Optional
{
    /**
     * Returns an empty {@see self::class} instance.
     */
    public static function empty(): static;

    /**
     * Returns an {@see self::class} with the specified present non-null value.
     *
     * @param T $value
     */
    public static function of(mixed $value): static;

    /**
     * Returns an {@see self::class} describing the specified value, if non-null, otherwise returns an empty {@see self::class}.
     *
     * @param T|null $value
     */
    public static function ofNullable(mixed $value): static;

    /**
     * Indicates whether some other object is "equal to" this {@see self::class}.
     */
    public function equals(mixed $obj): bool;

    /**
     * If a value is present, and the value matches the given predicate, return an {@see self::class} describing the value, otherwise return an empty {@see self::class}.
     *
     * @param callable(T): bool $predicate
     */
    public function filter(callable $predicate): static;

    /**
     * If a value is present, apply the provided {@see self::class}-bearing mapping function to it, return that result, otherwise return an empty {@see self::class}.
     *
     * @template U of mixed
     *
     * @param callable(T): self<U> $mapper
     *
     * @return self<U>
     */
    public function flatMap(callable $mapper): self;

    /**
     * If a value is present in this {@see self::class}, returns the value, otherwise throws {@see NoSuchElementException}.
     *
     * @return T
     *
     * @throws NoSuchElementException
     */
    public function get(): mixed;

    /**
     * If a value is present, invoke the specified consumer with the value, otherwise do nothing.
     *
     * @param callable(T): void $consumer
     */
    public function ifPresent(callable $consumer): void;

    /**
     * Return `true` if there is a value present, otherwise `false`.
     */
    public function isPresent(): bool;

    /**
     * If a value is present, apply the provided mapping function to it, and if the result is non-null, return an {@see self::class} describing the result.
     *
     * @template U of mixed
     *
     * @param callable(T): U $mapper
     *
     * @return self<U>
     */
    public function map(callable $mapper): self;

    /**
     * Return the value if present, otherwise return other.
     *
     * @param T $other
     *
     * @return T
     */
    public function orElse(mixed $other): mixed;

    /**
     * Return the value if present, otherwise invoke provided supplier and return the result of that invocation.
     *
     * @param callable(): T $otherSupplier
     *
     * @return T
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
