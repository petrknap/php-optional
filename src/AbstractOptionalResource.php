<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @deprecated use {@see OptionalResource}
 *
 * @todo merge it with {@see OptionalResource}
 *
 * @template-extends Optional<resource>
 */
abstract class AbstractOptionalResource extends Optional
{
    protected static function isSupported(mixed $value): bool
    {
        /** @var string $expectedResourceType */
        $expectedResourceType = static::getResourceType();
        return is_resource($value) && ($expectedResourceType === '' || get_resource_type($value) === $expectedResourceType);
    }

    /**
     * @see get_resource_type()
     *
     * @return non-empty-string
     */
    abstract protected static function getResourceType(): string;
}
