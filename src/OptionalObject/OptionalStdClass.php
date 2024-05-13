<?php

declare(strict_types=1);

namespace PetrKnap\Optional\OptionalObject;

use PetrKnap\Optional\OptionalObject;
use stdClass;

/**
 * @template-extends OptionalObject<stdClass>
 */
final class OptionalStdClass extends OptionalObject
{
    protected static function getObjectClassName(): string
    {
        return stdClass::class;
    }
}
