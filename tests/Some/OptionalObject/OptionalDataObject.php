<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Some\OptionalObject;

use PetrKnap\Optional\NonGenericOptional;
use PetrKnap\Optional\Some\DataObject;
use PetrKnap\Optional\Some\OptionalObject;

/**
 * @extends OptionalObject<DataObject>
 */
final class OptionalDataObject extends OptionalObject
{
    use NonGenericOptional;

    protected static function isSupported(mixed $value): bool
    {
        return $value instanceof DataObject;
    }
}
