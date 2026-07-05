<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Some;

use PetrKnap\Optional\GenericOptional;
use PetrKnap\Optional\Optional;

/**
 * @template-covariant T of array
 *
 * @extends Optional<T>
 */
final class OptionalArray extends Optional
{
    /** @use GenericOptional<T> */
    use GenericOptional;

    protected static function isSupported(mixed $value): bool
    {
        return is_array($value);
    }
}
