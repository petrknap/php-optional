<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Some;

use PetrKnap\Optional\NonGenericOptional;
use PetrKnap\Optional\Optional;

/**
 * @extends Optional<float>
 */
final class OptionalFloat extends Optional
{
    use NonGenericOptional;

    protected static function isSupported(mixed $value): bool
    {
        return is_float($value);
    }
}
