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

    /**
     * @return self<T>
     */
    public static function empty(): self
    {
        /** @var self<T> */
        return parent::empty();
    }

    /**
     * @template U of T
     *
     * @param U $value
     *
     * @return self<U>
     */
    public static function of(mixed $value): self
    {
        /** @var self<U> */
        return parent::of($value);
    }

    /**
     * @template U of T
     *
     * @param U|false $value
     *
     * @return self<U>
     */
    public static function ofFalsable(mixed $value): self
    {
        /** @var self<U> */
        return parent::ofFalsable($value);
    }

    /**
     * @template U of T
     *
     * @param U|null $value
     *
     * @return self<U>
     */
    public static function ofNullable(mixed $value): self
    {
        if (static::class === OptionalObject::class) {
            if ($value !== null) {
                try {
                    /** @var self<U> */
                    return TypedOptional::of($value, OptionalObject::class);
                } catch (Exception\CouldNotFindTypedOptionalForValue) {
                }
            }
            /** @var self<U> */
            return new class ($value) extends OptionalObject {
                protected static function isInstanceOfStatic(object $obj): bool
                {
                    return $obj instanceof OptionalObject;
                }
                protected static function getInstanceOf(): string
                {
                    TypedOptional::triggerNotice(OptionalObject::class . ' does not check the instance of object.');
                    /** @var class-string */
                    return self::ANY_INSTANCE_OF;
                }
            };
        }
        /** @var self<U> */
        return parent::ofNullable($value);
    }

    /**
     * @template U of T
     *
     * @param iterable<U> $value
     *
     * @return self<U>
     */
    public static function ofSingle(iterable $value): self
    {
        /** @var self<U> */
        return parent::ofSingle($value);
    }

    /**
     * @return self<T>
     */
    public function filter(callable $predicate): self
    {
        /** @var self<T> */
        return parent::filter($predicate);
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
