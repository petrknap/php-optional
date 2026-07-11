<?php

declare(strict_types=1);

namespace PetrKnap\Optional\OptionalObject;

use PetrKnap\Optional\OptionalObject;
use stdClass;

/**
 * @deprecated will be removed, use {@see Optional}
 *
 * @extends OptionalObject<stdClass>
 */
final class OptionalStdClass extends OptionalObject
{
    protected static function getInstanceOf(): string
    {
        return stdClass::class;
    }
}
