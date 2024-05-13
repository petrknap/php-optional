<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template-extends Optional<bool>
 */
final class OptionalBool extends Optional
{
    protected static function isSupported(mixed $value): bool
    {
        return is_bool($value);
    }
}
