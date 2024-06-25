<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use InvalidArgumentException;
use Throwable;

/**
 * @template T of mixed type of non-null value
 *
 * @implements JavaSe8\Optional<T>
 */
abstract class Optional implements JavaSe8\Optional
{
    private bool|null $wasPresent = null;

    /**
     * @param T|null $value
     */
    final protected function __construct(
        protected readonly mixed $value,
    ) {
        if ($this->value !== null && !@static::isSupported($this->value)) {
            throw new InvalidArgumentException('Value is not supported.');
        }
    }

    public static function empty(): static
    {
        return static::ofNullable(null);
    }

    public static function of(mixed $value): static
    {
        if ($value === null) {
            throw new InvalidArgumentException('Value must not be null.');
        }
        return static::ofNullable($value);
    }

    /**
     * Many PHP functions return `false` on failure, this is a factory for them.
     *
     * @param T|false $value
     */
    public static function ofFalsable(mixed $value): static
    {
        if ($value === false) {
            return static::empty();
        }
        return static::of($value);
    }

    public static function ofNullable(mixed $value): static
    {
        if (static::class === Optional::class) {
            if ($value !== null) {
                try {
                    /** @var static */
                    return TypedOptional::of($value, Optional::class);
                } catch (Exception\CouldNotFindTypedOptionalForValue) {
                }
            }
            return new class ($value) extends Optional {  # @phpstan-ignore-line
                protected static function isSupported(mixed $value): bool
                {
                    TypedOptional::triggerNotice(Optional::class . ' does not check the type of value.');
                    return true;
                }
            };
        }
        return new static($value);
    }

    public function equals(mixed $obj): bool
    {
        if ($obj instanceof static) {
            $obj = $obj->isPresent() ? $obj->get() : null;
        }
        return ($obj === null || static::isSupported($obj)) && $this->value == $obj;
    }

    public function filter(callable $predicate): static
    {
        if ($this->value !== null) {
            $matches = $predicate($this->value);
            if (!is_bool($matches)) {
                throw new InvalidArgumentException('Predicate must return boolean.');
            }
            if (!$matches) {
                return static::empty();
            }
        }
        return $this;
    }

    public function flatMap(callable $mapper): self  # @phpstan-ignore-line
    {
        if ($this->value !== null) {
            $mapped = $mapper($this->value);
            if (!$mapped instanceof self) {
                throw new InvalidArgumentException('Mapper must return instance of ' . self::class . '.');
            }
            return $mapped;
        }
        return $this;
    }

    public function get(): mixed
    {
        if ($this->wasPresent === null) {
            self::triggerNotice('Call `isPresent()` before accessing the value.');
        }
        return $this->orElseThrow();
    }

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

    public function map(callable $mapper): self  # @phpstan-ignore-line
    {
        /** @var callable(T): self<mixed> $flatMapper */
        $flatMapper = static function (mixed $value) use ($mapper): self {
            /** @var mixed $mapped */
            $mapped = $mapper($value);
            return Optional::ofNullable($mapped);
        };
        return $this->flatMap($flatMapper);
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
        $other = $otherSupplier();
        return static::isSupported($other) ? $other : throw new InvalidArgumentException('Other supplier must return supported other.');
    }

    /**
     * @template E of Throwable
     *
     * @param null|class-string<E>|callable(): E $exceptionSupplier
     *
     * @return T
     *
     * @throws E
     */
    public function orElseThrow(null|string|callable $exceptionSupplier = null): mixed
    {
        if ($exceptionSupplier === null) {
            return $this->orElseThrow(static fn () => new Exception\CouldNotGetValueOfEmptyOptional());
        }

        if (is_string($exceptionSupplier)) {
            /** @var class-string<E> $exceptionSupplier */
            if (!class_exists($exceptionSupplier, autoload: true)) {
                throw new InvalidArgumentException('Exception supplier must be existing class name.');
            }
            return $this->orElseThrow(static fn () => new $exceptionSupplier());
        }

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
     * @param mixed $value not null
     */
    abstract protected static function isSupported(mixed $value): bool;

    /**
     * @internal you should use {@see TypedOptional::triggerNotice()}
     */
    private static function triggerNotice(string $message): void
    {
        trigger_error(
            $message,
            error_level: E_USER_NOTICE,
        );
    }
}
