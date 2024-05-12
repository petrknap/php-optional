<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template-extends AbstractOptional<resource>
 */
abstract class AbstractOptionalResource extends AbstractOptional
{
    protected static function isSupported(mixed $value): bool
    {
        $expectedResourceType = static::getResourceType();
        return is_resource($value) && get_resource_type($value) === $expectedResourceType;
    }

    /**
     * @see get_resource_type()
     *
     * @return non-empty-string
     */
    abstract protected static function getResourceType(): string;
}
