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
            /** @var static */
            return new class ($value) extends Optional {
                protected static function isInstanceOfStatic(object $obj): bool
                {
                    return $obj instanceof Optional;
                }

                protected static function isSupported(mixed $value): bool
                {
                    TypedOptional::triggerNotice(Optional::class . ' does not check the type of value.');
                    return true;
                }
            };
        }
        return new static($value);
    }

    /**
     * @param bool $strict if `true` then the value, if the value is an object, will be compared as a reference
     */
    public function equals(mixed $obj, bool $strict = false): bool
    {
        if (!($obj instanceof JavaSe8\Optional)) {
            try {
                $obj = static::ofNullable($obj);
            } catch (InvalidArgumentException) {
                return false;
            }
        }

        if (static::isInstanceOfStatic($obj)) {
            $equals = null;
            $obj->ifPresent(function (mixed $objValue) use (&$equals, $strict): void {
                $equals = match (!is_object($this->value) || $strict) {
                    true => $this->value === $objValue,
                    false => $this->value == $objValue,
                };
            });
            return $equals ?? $this->value === null;
        }

        return false;
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

    /**
     * @template U of mixed
     *
     * @param callable(T): JavaSe8\Optional<U> $mapper
     *
     * @return self<U>
     */
    public function flatMap(callable $mapper): self
    {
        if ($this->value === null) {
            /** @var self<U> */
            return Optional::empty();
        }

        /** @var mixed $mapped */
        $mapped = $mapper($this->value);
        /** @var self<U> */
        return match (true) {
            $mapped instanceof self => $mapped,
            $mapped instanceof JavaSe8\Optional => $mapped->isPresent() ? Optional::of($mapped->get()) : Optional::empty(),
            default => throw new InvalidArgumentException('Mapper must return instance of ' . JavaSe8\Optional::class . '.'),
        };
    }

    public function get(): mixed
    {
        if ($this->wasPresent === null) {
            self::triggerNotice('Call `isPresent()` before accessing the value.');
        }
        return $this->orElseThrow();
    }

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
        return $this->wasPresent = $this->value !== null;
    }

    /**
     * @template U of mixed
     *
     * @param callable(T): U $mapper
     *
     * @return self<U>
     */
    public function map(callable $mapper): self
    {
        /** @var callable(T): self<U> $flatMapper */
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
     * @param null|class-string<E>|callable(string|null $message): E $exceptionSupplier
     *
     * @return T
     *
     * @throws E
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

    /**
     * Inverse of {@see self::ofNullable()}
     *
     * @return T|null
     */
    public function toNullable(): mixed
    {
        return $this->value;
    }

    /**
     * @internal overridden by abstracts
     */
    protected static function isInstanceOfStatic(object $obj): bool
    {
        return $obj instanceof static;
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
