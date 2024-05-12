<?php

declare(strict_types=1);

namespace PetrKnap\Optional\OptionalObject;

use PetrKnap\Optional\AbstractOptionalObject;
use stdClass;

/**
 * @template-extends AbstractOptionalObject<stdClass>
 */
final class OptionalStdClass extends AbstractOptionalObject
{
    protected static function getObjectClassName(): string
    {
        return stdClass::class;
    }
}
