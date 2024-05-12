<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template-extends AbstractOptional<string>
 */
final class OptionalString extends AbstractOptional
{
    protected static function isSupported(mixed $value): bool
    {
        return is_string($value);
    }
}
