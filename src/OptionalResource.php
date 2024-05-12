<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use PetrKnap\Shorts\Exception\NotImplemented;

/**
 * Please use another implementation of {@see AbstractOptionalResource} if possible.
 */
final class OptionalResource extends AbstractOptionalResource
{
    protected static function isSupported(mixed $value): bool
    {
        trigger_error(
            self::class . ' does not check the type of resource.',
            error_level: E_USER_NOTICE,
        );
        return is_resource($value);
    }

    protected static function getResourceType(): string
    {
        NotImplemented::throw(__METHOD__);
    }
}
