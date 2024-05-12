<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template-extends AbstractOptional<bool>
 */
final class OptionalBool extends AbstractOptional
{
    protected static function isSupported(mixed $value): bool
    {
        return is_bool($value);
    }
}
