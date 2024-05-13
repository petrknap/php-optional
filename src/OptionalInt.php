<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template-extends Optional<int>
 */
final class OptionalInt extends Optional
{
    protected static function isSupported(mixed $value): bool
    {
        return is_int($value);
    }
}
