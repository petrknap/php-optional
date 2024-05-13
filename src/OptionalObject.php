<?php

/**
 * Please use subclass of {@see OptionalObject} if possible.
 */

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template T of object
 *
 * @template-extends AbstractOptionalObject<object>
 */
/* abstract */ class OptionalObject extends AbstractOptionalObject
{
    /* abstract */ protected static function getObjectClassName(): string
    {
        trigger_error(
            static::class . ' does not check the instance of object.',
            error_level: E_USER_NOTICE,
        );
        /** @var class-string */
        return '';
    }
}
