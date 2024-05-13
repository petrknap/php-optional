<?php

declare(strict_types=1);

namespace PetrKnap\Optional\OptionalResource;

use PetrKnap\Optional\OptionalResource;

final class OptionalStream extends OptionalResource
{
    protected static function getResourceType(): string
    {
        return 'stream';
    }
}
