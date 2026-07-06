<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * Provides internal logic for abstract {@see Optional}
 *
 * @template-covariant T of mixed type of non-null value
 *
 * @phpstan-require-extends Optional
 */
trait AbstractOptional
{
    /** @use GenericOptional<T> */
    use GenericOptional;

    /**
     * @template U of T
     *
     * @param U|null $value
     *
     * @return self<U>
     */
    public static function ofNullable(mixed $value): self // @phpstan-ignore generics.notSubtype
    {
        if (static::class === self::class) {
            if ($value !== null) {
                try {
                    /** @var self<U> */ // @phpstan-ignore generics.notSubtype
                    return TypedOptional::of($value, static::class);
                } catch (Exception\CouldNotFindTypedOptionalForValue) {
                }
            }
            /** @var self<U> */
            return self::createInstance($value); // @phpstan-ignore generics.notSubtype, argument.type, argument.templateType
        }
        /** @var self<U> */
        return parent::ofNullable($value); // @phpstan-ignore generics.notSubtype, argument.type, argument.templateType
    }

    /**
     * @template U of T
     *
     * @param U|null $value
     *
     * @return self<U>
     */
    abstract protected static function createInstance(mixed $value): self;

    abstract protected static function isInstanceOfStatic(object $obj): bool;
}
