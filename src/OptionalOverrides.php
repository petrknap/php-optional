<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use InvalidArgumentException;

/**
 * Overrides returns from {@see Optional} to `self`
 *
 * @template-covariant T of mixed type of non-null value
 *
 * @phpstan-require-extends Optional
 */
trait OptionalOverrides
{
    /**
     * @return self<T>
     */
    public static function empty(): self
    {
        /** @var self<T> */
        return parent::empty();
    }

    /**
     * @template U of T
     *
     * @param U $value
     *
     * @return (U is null ? never : self<U>)
     *
     * @throws InvalidArgumentException
     */
    public static function of(mixed $value): self
    {
        /** @var self<U> */
        return parent::of($value);
    }

    /**
     * In many cases a failure returns `false`, this method serves as a factory for them.
     *
     * @template U of T
     *
     * @param U|false $value
     *
     * @return (U is null ? never : self<U>)
     *
     * @throws InvalidArgumentException
     */
    public static function ofFalsable(mixed $value): self
    {
        /** @var self<U> */
        return parent::ofFalsable($value);
    }

    /**
     * @template U of T
     *
     * @param U|null $value
     *
     * @return self<U>
     */
    public static function ofNullable(mixed $value): self
    {
        /** @var self<U> */
        return parent::ofNullable($value);
    }

    /**
     * In many cases you will receive an `iterable` of single value, this method serves as a factory for them.
     *
     * @template U of T
     *
     * @param iterable<U> $value
     *
     * @return (U is null ? ($value is empty ? self<U> : never) : self<U>)
     *
     * @throws InvalidArgumentException
     */
    public static function ofSingle(iterable $value): self
    {
        /** @var self<U> */
        return parent::ofSingle($value);
    }

    /**
     * @return self<T>
     */
    public function filter(callable $predicate): self
    {
        /** @var self<T> */
        return parent::filter($predicate);
    }

    /**
     * @template U of mixed type of non-null value
     *
     * @param callable(T): U $mapper
     *
     * @return self<U>
     */
    public function map(callable $callback): self
    {
        return self::ofNullable(parent::map($callback)->orElse(null));
    }
}
