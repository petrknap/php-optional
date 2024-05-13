<?php

/**
 * Please use subclass of {@see OptionalResource} if possible.
 */

declare(strict_types=1);

namespace PetrKnap\Optional;

/* abstract */ class OptionalResource extends AbstractOptionalResource
{
    /* abstract */ protected static function getResourceType(): string
    {
        trigger_error(
            static::class . ' does not check the type of resource.',
            error_level: E_USER_NOTICE,
        );
        /** @var non-empty-string */
        return '';
    }
}
