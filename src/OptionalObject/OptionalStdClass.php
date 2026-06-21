<?php

declare(strict_types=1);

namespace PetrKnap\Optional\OptionalObject;

use PetrKnap\Optional\NonGenericOptional;
use PetrKnap\Optional\OptionalObject;
use stdClass;

/**
 * @extends OptionalObject<stdClass>
 */
final class OptionalStdClass extends OptionalObject
{
    use NonGenericOptional;

    protected static function getInstanceOf(): string
    {
        return stdClass::class;
    }
}
