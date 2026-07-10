<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * Provides type hints for generic {@see Optional}
 *
 * @template-covariant T of mixed type of non-null value
 *
 * @phpstan-require-extends Optional
 */
trait GenericOptional
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
    public static function of(mixed $value): self // @phpstan-ignore generics.notSubtype
    {
        /** @var self<U> */
        return parent::of($value); // @phpstan-ignore generics.notSubtype, argument.type, argument.templateType
    }

    /**
     * @template U of T
     *
     * @param U|false $value
     *
     * @return self<U>
     */
    public static function ofFalsable(mixed $value): self // @phpstan-ignore generics.notSubtype
    {
        /** @var self<U> */
        return parent::ofFalsable($value); // @phpstan-ignore generics.notSubtype, argument.type, argument.templateType
    }

    /**
     * @template U of T
     *
     * @param U|null $value
     *
     * @return self<U>
     */
    public static function ofNullable(mixed $value): self // @phpstan-ignore generics.notSubtype
    {
        /** @var self<U> */
        return parent::ofNullable($value); // @phpstan-ignore generics.notSubtype, argument.type, argument.templateType
    }

    /**
     * @template U of T
     *
     * @param iterable<U> $value
     *
     * @return self<U>
     */
    public static function ofSingle(iterable $value): self // @phpstan-ignore generics.notSubtype
    {
        /** @var self<U> */
        return parent::ofSingle($value); // @phpstan-ignore generics.notSubtype, argument.type, argument.templateType
    }

    /**
     * @return self<T>
     */
    public function filter(callable $predicate): self // @phpstan-ignore generics.notSubtype
    {
        /** @var self<T> */
        return parent::filter($predicate); // @phpstan-ignore generics.notSubtype, argument.type, argument.templateType
    }
}
