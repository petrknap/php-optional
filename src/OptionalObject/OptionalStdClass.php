<?php

declare(strict_types=1);

namespace PetrKnap\Optional\OptionalObject;

use PetrKnap\Optional\OptionalObject;
use stdClass;

/**
 * @extends OptionalObject<stdClass>
 */
final class OptionalStdClass extends OptionalObject
{
    protected static function getInstanceOf(): string
    {
        return stdClass::class;
    }
}
