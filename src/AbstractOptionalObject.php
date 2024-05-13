<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @deprecated use {@see OptionalObject}
 *
 * @todo merge it with {@see OptionalObject}
 *
 * @template T of object
 *
 * @template-extends Optional<T>
 */
abstract class AbstractOptionalObject extends Optional
{
    protected static function isSupported(mixed $value): bool
    {
        /** @var string $expectedObjectClassName */
        $expectedObjectClassName = static::getObjectClassName();
        return is_object($value) && ($expectedObjectClassName === '' || $value instanceof $expectedObjectClassName);
    }

    /**
     * @return class-string
     */
    abstract protected static function getObjectClassName(): string;
}
