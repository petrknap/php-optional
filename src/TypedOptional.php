<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use InvalidArgumentException;

final class TypedOptional
{
    private static bool|null $enabledErrorTriggering = null;

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
        foreach (array_reverse(self::$typedOptionals) as $typedOptional) {
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
        if (self::$enabledErrorTriggering === null) {
            self::enableErrorTriggering();
        }
        self::$typedOptionals[] = $typedOptionalClassName;
    }

    public static function enableErrorTriggering(): void
    {
        self::$enabledErrorTriggering = true;
    }

    public static function disableErrorTriggering(): void
    {
        self::$enabledErrorTriggering = false;
    }

    public static function triggerNotice(string $message): void
    {
        if (self::$enabledErrorTriggering === true) {
            trigger_error(
                $message,
                error_level: E_USER_NOTICE,
            );
        }
    }
}
