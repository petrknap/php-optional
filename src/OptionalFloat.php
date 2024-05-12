<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template-extends AbstractOptional<float>
 */
final class OptionalFloat extends AbstractOptional
{
    protected static function isSupported(mixed $value): bool
    {
        return is_float($value);
    }
}
