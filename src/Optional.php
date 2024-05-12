<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use InvalidArgumentException;
use Throwable;

/**
 * @template T of mixed type of non-null value
 *
 * @see https://docs.oracle.com/javase/8/docs/api/java/util/Optional.html
 */
class Optional
{
    private bool|null $wasPresent = null;

    /**
     * @deprecated will be changed to protected - use {@see self::ofNullable()}/{@see self::of()}/{@see self::empty()}
     *
     * @param T|null $value
     */
    final public function __construct(
        protected readonly mixed $value,
    ) {
        if ($this->value !== null && !static::isSupported($this->value)) {
            throw new InvalidArgumentException('Value is not supported.');
        }
    }

    public static function empty(): static
    {
        return new static(null);
    }

    /**
     * @param T $value
     */
    public static function of(mixed $value): static
    {
        return $value !== null ? new static($value) : throw new InvalidArgumentException('Value must not be null.');
    }

    /**
     * @param T|null $value
     */
    public static function ofNullable(mixed $value): static
    {
        return new static($value);
    }

    public function equals(mixed $obj): bool
    {
        if ($obj instanceof static) {
            $obj = $obj->isPresent() ? $obj->get() : null;
        }
        return ($obj === null || static::isSupported($obj)) && $this->value == $obj;
    }

    /**
     * @return T
     *
     * @throws Exception\NoSuchElement
     */
    public function get(): mixed
    {
        if ($this->wasPresent === null) {
            trigger_error(
                'Call `isPresent()` before accessing the value.',
                error_level: E_USER_NOTICE,
            );
        }
        return $this->orElseThrow(static fn (): Exception\NoSuchElement => new Exception\NoSuchElement());
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
        return $this->orElseGet(static fn (): mixed => $other);
    }

    /**
     * @param callable(): T $otherSupplier
     *
     * @return T
     */
    public function orElseGet(callable $otherSupplier): mixed
    {
        if ($this->value !== null) {
            return $this->value;
        }
        $other = $otherSupplier();
        return static::isSupported($other) ? $other : throw new InvalidArgumentException('Other supplier must return supported other.');
    }

    /**
     * @template E of Throwable
     *
     * @param callable(): E $exceptionSupplier
     *
     * @return T
     *
     * @throws E
     */
    public function orElseThrow(callable $exceptionSupplier): mixed
    {
        return $this->orElseGet(static function () use ($exceptionSupplier): never {
            /** @var Throwable|mixed $exception */
            $exception = $exceptionSupplier();
            if ($exception instanceof Throwable) {
                throw $exception;
            }
            throw new InvalidArgumentException('Exception supplier must return ' . Throwable::class . '.');
        });
    }

    /**
     * @param T|mixed $value not null
     */
    protected static function isSupported(mixed $value): bool
    {
        trigger_error(
            static::class . ' does not check the type of value.',
            error_level: E_USER_NOTICE,
        );
        return $value !== null;
    }
}
