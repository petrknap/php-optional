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
        self::logNotice(static::class . ' does not check the type of resource.');
        /** @var non-empty-string */
        return '';
    }
}
