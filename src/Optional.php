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
        return $value !== null ? self::ofNullable($value) : throw new InvalidArgumentException('Value must not be null.');
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
        return $otherSupplier() ?? throw new InvalidArgumentException('Other supplier must return value.');
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
}
