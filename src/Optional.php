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
     * @param T|null $value
     */
    public function __construct(
        private readonly mixed $value,
    ) {
    }

    /**
     * @return self<null>
     */
    public static function empty(): self
    {
        return new self(null);
    }

    public function isPresent(): bool
    {
        return $this->wasPresent = $this->value !== null;
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
     * @param T $other
     *
     * @return T
     */
    public function orElse(mixed $other): mixed
    {
        return $this->value ?? $other;
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
}
