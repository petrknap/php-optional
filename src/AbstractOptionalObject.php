<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template T of object
 *
 * @template-extends AbstractOptional<T>
 */
abstract class AbstractOptionalObject extends AbstractOptional
{
    protected static function isSupported(mixed $value): bool
    {
        $expectedObjectClassName = static::getObjectClassName();
        return $value instanceof $expectedObjectClassName;
    }

    /**
     * @return class-string
     */
    abstract protected static function getObjectClassName(): string;
}
