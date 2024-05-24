<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @extends Optional<string>
 */
final class OptionalString extends Optional
{
    protected static function isSupported(mixed $value): bool
    {
        return is_string($value);
    }
}
