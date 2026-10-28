<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use InvalidArgumentException;
use Throwable;

/**
 * @template-covariant T of mixed type of non-null value
 *
 * @implements JavaSe8\Optional<T>
 */
abstract class Optional implements JavaSe8\Optional
{
    /**
     * @param T|null $value
     */
    final protected function __construct(
        protected readonly mixed $value,
    ) {
    }

    /**
     * @return self<T>
     */
    public static function empty(): self
    {
        /** @var T|null $typedNull */
        $typedNull = null;
        return static::ofNullable($typedNull);
    }

    /**
     * @template U of T
     *
     * @param U $value
     *
     * @return (U is null ? never : self<U>)
     *
     * @throws InvalidArgumentException
     */
    public static function of(mixed $value): self
    {
        if ($value === null) {
            throw new InvalidArgumentException('Value must not be null.');
        }
        return static::ofNullable($value);
    }

    /**
     * In many cases a failure returns `false`, this method serves as a factory for them.
     *
     * @template U of T
     *
     * @param U|false $value
     *
     * @return (U is null ? never : self<U>)
     *
     * @throws InvalidArgumentException
     */
    public static function ofFalsable(mixed $value): self
    {
        if ($value === false) {
            /** @var self<U> */
            return static::empty();
        }
        return static::of($value);
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
        if (static::class === Optional::class) {
            /** @var self<U> */
            return new class ($value) extends Optional {
            };
        }
        return new static($value);
    }

    /**
     * In many cases you will receive an `iterable` of single value, this method serves as a factory for them.
     *
     * @template U of T
     *
     * @param iterable<U> $values
     *
     * @return (U is null ? ($values is empty ? self<U> : never) : self<U>)
     *
     * @throws InvalidArgumentException
     */
    public static function ofSingle(iterable $values): self
    {
        $option = null;
        foreach ($values as $value) {
            if ($option !== null) {
                throw new InvalidArgumentException('Value is not single.');
            }
            $option = static::of($value);
        }
        /** @var self<U> */
        return $option ?? static::empty();
    }

    /**
     * @param ($this is JavaSe8\Optional<object> ? ($obj is JavaSe8\Optional<object> ? bool : false) : false) $byReference if `false`, wrapped objects will be compared as records
     *
     * @return ($obj is JavaSe8\Optional<mixed> ? bool : false)
     *
     * @note `$byReference` defaults to `false` because wrapped objects are expected to be records. While for classes
     *       `Optional.of(new Class("value")).equals(Optional.of(new Class("value")))` returns `false`, for records
     *       `Optional.of(new Record("value")).equals(Optional.of(new Record("value")))` returns `true`.
     */
    public function equals(mixed $obj, bool $byReference = false): bool
    {
        if (!($obj instanceof JavaSe8\Optional)) {
            return false;
        }

        $value = $obj->orElse(null);

        if (!$byReference && is_object($this->value) && is_object($value)) {
            return $this->value == $value; // @phpstan-ignore equal.invalid
        }

        return $this->value === $value;
    }

    /**
     * @return self<T>
     */
    public function filter(callable $predicate): self
    {
        if ($this->value === null) {
            return $this;
        }

        return match ($predicate($this->value)) {
            true => $this,
            false=> static::empty(),
        };
    }

    /**
     * @template U of mixed type of non-null value
     * @template V of JavaSe8\Optional<U>
     *
     * @param callable(T): V $mapper
     *
     * @return V|self<U>
     */
    public function flatMap(callable $mapper): JavaSe8\Optional
    {
        if ($this->value === null) {
            return $this;
        }

        return $mapper($this->value);
    }

    /**
     * @see self::orElseThrow() - more explicit alternative
     */
    public function get(): mixed
    {
        return $this->orElseThrow();
    }

    /**
     * @param null|callable(): mixed $else
     */
    public function ifPresent(callable $consumer, callable|null $else = null): void
    {
        if ($this->value !== null) {
            $consumer($this->value);
        } elseif ($else !== null) {
            $else();
        }
    }

    public function isPresent(): bool
    {
        return $this->value !== null;
    }

    /**
     * @template U of mixed type of non-null value
     *
     * @param callable(T): U $mapper
     *
     * @return self<U>
     */
    public function map(callable $mapper): self
    {
        if ($this->value === null) {
            return $this;
        }
        /** @var self<U> */
        return static::ofNullable($mapper($this->value)); // @phpstan-ignore argument.type, argument.templateType
    }

    public function orElse(mixed $other): mixed
    {
        return $this->orElseGet(static fn (): mixed => $other);
    }

    public function orElseGet(callable $otherSupplier): mixed
    {
        if ($this->value !== null) {
            return $this->value;
        }
        /** @var T|null */
        return $otherSupplier();
    }

    /**
     * @template E of Throwable
     *
     * @param null|class-string<E>|callable(string|null $message): E $exceptionSupplier
     *
     * @return T
     *
     * @throws ($exceptionSupplier is null ? Exception\CouldNotGetValueOfEmptyOptional : E)
     */
    public function orElseThrow(
        null|string|callable $exceptionSupplier = null,
        string|null $message = null,
    ): mixed {
        if ($exceptionSupplier === null) {
            return $this->orElseThrow(static fn () => match ($message) {
                null => new Exception\CouldNotGetValueOfEmptyOptional(),
                default => new Exception\CouldNotGetValueOfEmptyOptional($message),
            });
        }

        if (is_string($exceptionSupplier)) {
            /** @var class-string<E> $exceptionSupplier */
            if (!class_exists($exceptionSupplier, autoload: true)) {
                throw new InvalidArgumentException('Exception supplier must be existing class name.');
            }
            return $this->orElseThrow(static fn () => match ($message) {
                null => new $exceptionSupplier(),
                default => new $exceptionSupplier($message),
            });
        }

        return $this->orElseGet(static function () use ($exceptionSupplier, $message): never {
            /** @var Throwable|mixed $exception */
            $exception = $exceptionSupplier($message);
            if ($exception instanceof Throwable) {
                throw $exception;
            }
            throw new InvalidArgumentException('Exception supplier must return ' . Throwable::class . '.');
        });
    }
}
