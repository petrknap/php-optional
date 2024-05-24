<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @template T of object
 *
 * @extends Optional<T>
 */
abstract class OptionalObject extends Optional
{
    /** @internal */
    protected const ANY_INSTANCE_OF = '';

    public static function ofNullable(mixed $value): static
    {
        if (static::class === OptionalObject::class) {
            return new class ($value) extends OptionalObject {  # @phpstan-ignore-line
                protected static function getInstanceOf(): string
                {
                    self::logNotice(OptionalObject::class . ' does not check the instance of object.');
                    /** @var class-string */
                    return self::ANY_INSTANCE_OF;
                }
            };
        }
        return parent::ofNullable($value);
    }

    protected static function isSupported(mixed $value): bool
    {
        /** @var string $expectedInstanceOf */
        $expectedInstanceOf = static::getInstanceOf();
        return is_object($value) && ($expectedInstanceOf === self::ANY_INSTANCE_OF || $value instanceof $expectedInstanceOf);
    }

    /**
     * @return class-string
     */
    abstract protected static function getInstanceOf(): string;
}
