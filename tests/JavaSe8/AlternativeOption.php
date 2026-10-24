<?php

declare(strict_types=1);

namespace PetrKnap\Optional\JavaSe8;

use PetrKnap\Shorts\Exception\NotImplemented;

final class AlternativeOption implements Optional
{
    public static function empty(): self
    {
        return new self();
    }

    public static function of(mixed $value): never
    {
        NotImplemented::throw(__METHOD__);
    }

    public static function ofNullable(mixed $value): never
    {
        NotImplemented::throw(__METHOD__);
    }

    public function equals(mixed $obj): never
    {
        NotImplemented::throw(__METHOD__);
    }

    public function filter(callable $predicate): never
    {
        NotImplemented::throw(__METHOD__);
    }

    public function flatMap(callable $mapper): never
    {
        NotImplemented::throw(__METHOD__);
    }

    public function get(): never
    {
        NotImplemented::throw(__METHOD__);
    }

    public function ifPresent(callable $consumer): never
    {
        NotImplemented::throw(__METHOD__);
    }

    public function isPresent(): never
    {
        NotImplemented::throw(__METHOD__);
    }

    public function map(callable $mapper): never
    {
        NotImplemented::throw(__METHOD__);
    }

    public function orElse(mixed $other): mixed
    {
        return $other;
    }

    public function orElseGet(callable $otherSupplier): never
    {
        NotImplemented::throw(__METHOD__);
    }

    public function orElseThrow(callable $exceptionSupplier): never
    {
        NotImplemented::throw(__METHOD__);
    }
}
