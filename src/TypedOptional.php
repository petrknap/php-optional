<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use InvalidArgumentException;

/**
 * @internal use {@see Optional}
 */
final class TypedOptional
{
    /** @var array<class-string> must be iterated in reverse order */
    private static array $typedOptionals = [
        OptionalArray::class,
        OptionalBool::class,
        OptionalFloat::class,
        OptionalInt::class,
        OptionalObject::class,
        OptionalObject\OptionalStdClass::class,
        OptionalResource::class,
        OptionalResource\OptionalStream::class,
        OptionalString::class,
    ];

    /**
     * @template T of mixed type of non-null value
     *
     * @param T $value
     *
     * @return Optional<T>
     *
     * @throws Exception\CouldNotFindTypedOptionalForValue
     */
    public static function of(mixed $value): Optional
    {
        /** @var class-string<Optional<T>> $typedOptional */
        foreach (array_reverse(self::$typedOptionals) as $typedOptional) {
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
        self::$typedOptionals[] = $typedOptionalClassName;
    }
}
