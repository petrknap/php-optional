<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template-extends Optional<float>
 */
final class OptionalFloat extends Optional
{
    protected static function isSupported(mixed $value): bool
    {
        return is_float($value);
    }
}
