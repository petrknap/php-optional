<?php

declare(strict_types=1);

namespace PetrKnap\Optional\OptionalResource;

use PetrKnap\Optional\NonGenericOptional;
use PetrKnap\Optional\OptionalResource;

final class OptionalStream extends OptionalResource
{
    use NonGenericOptional;

    protected static function getResourceType(): string
    {
        return 'stream';
    }
}
