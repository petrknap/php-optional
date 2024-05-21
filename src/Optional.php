<?php

/**
 * Please use subclass of {@see Optional} if possible.
 */

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template T of mixed
 *
 * @template-extends AbstractOptional<T>
 */
/* abstract */ class Optional extends AbstractOptional
{
    /* abstract */ protected static function isSupported(mixed $value): bool
    {
        self::logNotice(static::class . ' does not check the type of value.');
        return true;
    }
}
