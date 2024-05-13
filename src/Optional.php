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
        trigger_error(
            static::class . ' does not check the type of value.',
            error_level: E_USER_NOTICE,
        );
        return true;
    }
}
