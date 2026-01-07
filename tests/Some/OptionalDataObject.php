<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Some;

use PetrKnap\Optional\NonGenericOptional;
use PetrKnap\Optional\OptionalObject;

/**
 * @extends OptionalObject<DataObject>
 */
final class OptionalDataObject extends OptionalObject
{
    use NonGenericOptional;

    protected static function getInstanceOf(): string
    {
        return DataObject::class;
    }
}
