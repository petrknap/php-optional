<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @extends Optional<resource>
 */
abstract class OptionalResource extends Optional
{
    /** @internal */
    protected const ANY_RESOURCE_TYPE = '';

    public static function ofNullable(mixed $value): static
    {
        if (static::class === OptionalResource::class) {
            if ($value !== null) {
                try {
                    /** @var static */
                    return TypedOptional::of($value, OptionalResource::class);
                } catch (Exception\CouldNotFindTypedOptionalForValue) {
                }
            }
            /** @var static */
            return new class ($value) extends OptionalResource {
                protected static function isInstanceOfStatic(object $obj): bool
                {
                    return $obj instanceof OptionalResource;
                }
                protected static function getResourceType(): string
                {
                    TypedOptional::triggerNotice(OptionalResource::class . ' does not check the type of resource.');
                    /** @var non-empty-string */
                    return self::ANY_RESOURCE_TYPE;
                }
            };
        }
        return parent::ofNullable($value);
    }

    protected static function isSupported(mixed $value): bool
    {
        /** @var string $expectedResourceType */
        $expectedResourceType = static::getResourceType();
        return is_resource($value) && ($expectedResourceType === self::ANY_RESOURCE_TYPE || get_resource_type($value) === $expectedResourceType);
    }

    /**
     * @see get_resource_type()
     *
     * @return non-empty-string
     */
    abstract protected static function getResourceType(): string;
}
