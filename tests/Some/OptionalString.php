<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Some;

use PetrKnap\Optional\NonGenericOptional;
use PetrKnap\Optional\Optional;

/**
 * Example of using the {@see NonGenericOptional} trait
 *
 * @extends Optional<string>
 */
final class OptionalString extends Optional
{
    use NonGenericOptional;

    protected static function isSupported(mixed $value): bool
    {
        return is_string($value);
    }
}
