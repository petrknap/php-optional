<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use LogicException;

/**
 * @template T of mixed type of non-null value
 *
 * @see https://docs.oracle.com/javase/8/docs/api/java/util/Optional.html
 */
final class Optional
{
    private bool|null $wasPresent = null;

    /**
     * @deprecated will be changed to protected - use {@see self::ofNullable()}/{@see self::of()}/{@see self::empty()}
     *
     * @param T|null $value
     */
    public function __construct(
        private readonly mixed $value,
    ) {
    }

    /**
     * @return self<T>
     */
    public static function empty(): self
    {
        return self::ofNullable(null);
    }

    /**
     * @param T $value
     *
     * @return self<T>
     */
    public static function of(mixed $value): self
    {
        return $value !== null ? self::ofNullable($value) : throw new LogicException('Value must not be null.');
    }

    /**
     * @param T|null $value
     *
     * @return self<T>
     */
    public static function ofNullable(mixed $value): self
    {
        return new self($value);
    }

    public function equals(mixed $obj): bool
    {
        if ($obj instanceof Optional) {
            $obj = $obj->isPresent() ? $obj->get() : null;
        }
        return $this->value === $obj;
    }

    /**
     * @return T
     *
     * @throws Exception\NoSuchElement
     */
    public function get(): mixed
    {
        /** @var T */
        return match ($this->wasPresent) {
            true => $this->value,
            false => throw new Exception\NoSuchElement(),
            null => throw new LogicException('Call `isPresent()` before accessing the value.'),
        };
    }

    /**
     * @param callable(T): void $consumer
     */
    public function ifPresent(callable $consumer): void
    {
        if ($this->value !== null) {
            $consumer($this->value);
        }
    }

    public function isPresent(): bool
    {
        return $this->wasPresent = $this->value !== null;
    }

    /**
     * @param T $other
     *
     * @return T
     */
    public function orElse(mixed $other): mixed
    {
        return $this->value ?? $other;
    }
}
