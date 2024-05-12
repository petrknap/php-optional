<?php

declare(strict_types=1);

namespace PetrKnap\Optional\OptionalResource;

use PetrKnap\Optional\AbstractOptionalResource;

final class OptionalStream extends AbstractOptionalResource
{
    protected static function getResourceType(): string
    {
        return 'stream';
    }
}
