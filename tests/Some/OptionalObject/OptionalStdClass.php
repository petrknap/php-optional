<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Some\OptionalObject;

use PetrKnap\Optional\NonGenericOptional;
use PetrKnap\Optional\Some\OptionalObject;
use stdClass;

/**
 * @extends OptionalObject<stdClass>
 */
final class OptionalStdClass extends OptionalObject
{
    use NonGenericOptional;

    protected static function isSupported(mixed $value): bool
    {
        return $value instanceof stdClass;
    }
}
