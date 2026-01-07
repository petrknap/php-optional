<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template T of array
 *
 * @extends Optional<T>
 */
final class OptionalArray extends Optional
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
     * @return self<U>
     */
    public static function of(mixed $value): self
    {
        /** @var self<U> */
        return parent::of($value);
    }

    /**
     * @template U of T
     *
     * @param U|false $value
     *
     * @return self<U>
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
     * @template U of T
     *
     * @param iterable<U> $value
     *
     * @return self<U>
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

    protected static function isSupported(mixed $value): bool
    {
        return is_array($value);
    }
}
