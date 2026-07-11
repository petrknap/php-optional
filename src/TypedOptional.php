<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use InvalidArgumentException;

final class TypedOptional
{
    /** @var array<class-string> stored in reverse order, see {@see self::register()} */
    private static array $typedOptionals = [];

    /**
     * @internal use {@see Optional::of()}
     *
     * @template T of mixed type of non-null value
     *
     * @param T $value
     * @param class-string $subclassOf
     *
     * @return Optional<T>
     *
     * @throws Exception\CouldNotFindTypedOptionalForValue
     */
    public static function of(mixed $value, string $subclassOf): Optional
    {
        /** @var class-string<Optional<T>> $typedOptional */
        foreach (self::$typedOptionals as $typedOptional) {
            if ($typedOptional === $subclassOf || !is_a($typedOptional, $subclassOf, allow_string: true)) {
                continue;
            }
            try {
                return $typedOptional::of($value);
            } catch (InvalidArgumentException) {
            }
        }
        throw new Exception\CouldNotFindTypedOptionalForValue($value);
    }

    /**
     * @param class-string $typedOptionalClassName
     *
     * @throws Exception\CouldNotRegisterNonOptional
     */
    public static function register(string $typedOptionalClassName): void
    {
        if (!is_a($typedOptionalClassName, Optional::class, allow_string: true)) {
            throw new Exception\CouldNotRegisterNonOptional($typedOptionalClassName);
        }
        array_unshift(self::$typedOptionals, $typedOptionalClassName);
    }
}
