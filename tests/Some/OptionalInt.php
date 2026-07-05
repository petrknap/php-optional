<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Some;

use PetrKnap\Optional\NonGenericOptional;
use PetrKnap\Optional\Optional;

/**
 * @extends Optional<int>
 */
final class OptionalInt extends Optional
{
    use NonGenericOptional;

    protected static function isSupported(mixed $value): bool
    {
        return is_int($value);
    }
}
