<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template K of array-key
 * @template V of mixed
 *
 * @template-extends AbstractOptional<array<K, V>>
 */
final class OptionalArray extends AbstractOptional
{
    protected static function isSupported(mixed $value): bool
    {
        return is_array($value);
    }
}
