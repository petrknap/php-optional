<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use InvalidArgumentException;
use Throwable;

/**
 * Please use another implementation of {@see AbstractOptional} if possible.
 *
 * @todo make it final
 *
 * @deprecated will be converted to final
 *
 * @template T of mixed
 *
 * @template-extends AbstractOptional<T>
 */
class Optional extends AbstractOptional
{
    protected static function isSupported(mixed $value): bool
    {
        trigger_error(
            static::class . ' does not check the type of value.',
            error_level: E_USER_NOTICE,
        );
        return $value !== null;
    }
}
