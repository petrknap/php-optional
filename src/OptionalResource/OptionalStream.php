<?php

declare(strict_types=1);

namespace PetrKnap\Optional\OptionalResource;

use PetrKnap\Optional\OptionalResource;

/**
 * @deprecated will be removed, use {@see Optional}
 */
final class OptionalStream extends OptionalResource
{
    protected static function getResourceType(): string
    {
        return 'stream';
    }
}
