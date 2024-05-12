<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use PetrKnap\Shorts\Exception\NotImplemented;

/**
 * Please use another implementation of {@see AbstractOptionalObject} if possible.
 *
 * @template T of object
 *
 * @template-extends AbstractOptionalObject<object>
 */
final class OptionalObject extends AbstractOptionalObject
{
    protected static function isSupported(mixed $value): bool
    {
        trigger_error(
            self::class . ' does not check the instance of object.',
            error_level: E_USER_NOTICE,
        );
        return is_object($value);
    }

    protected static function getObjectClassName(): string
    {
        NotImplemented::throw(__METHOD__);
    }
}
