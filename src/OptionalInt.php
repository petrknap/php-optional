<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template-extends AbstractOptional<int>
 */
final class OptionalInt extends AbstractOptional
{
    protected static function isSupported(mixed $value): bool
    {
        return is_int($value);
    }
}
