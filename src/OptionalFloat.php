<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

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
