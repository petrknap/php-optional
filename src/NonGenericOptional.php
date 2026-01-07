<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * Sets return types to non-generic {@see Optional}
 *
 * @phpstan-require-extends Optional
 */
trait NonGenericOptional
{
    public static function empty(): self
    {
        /** @var self */
        return parent::empty();
    }

    public static function of(mixed $value): self
    {
        /** @var self */
        return parent::of($value);
    }

    public static function ofFalsable(mixed $value): self
    {
        /** @var self */
        return parent::ofFalsable($value);
    }

    public static function ofNullable(mixed $value): self
    {
        /** @var self */
        return parent::ofNullable($value);
    }

    public static function ofSingle(iterable $value): self
    {
        /** @var self */
        return parent::ofSingle($value);
    }

    public function filter(callable $predicate): self
    {
        /** @var self */
        return parent::filter($predicate);
    }
}
