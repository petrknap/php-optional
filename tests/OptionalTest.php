<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use LogicException;
use PHPUnit\Framework\TestCase;

class OptionalTest extends TestCase
{
    private const VALUE = 'test';

    public function testGetReturnsValueWhenValueIsPresent(): void
    {
        $optional = new Optional(self::VALUE);

        self::assertTrue($optional->isPresent());
        self::assertSame(self::VALUE, $optional->get());
    }

    public function testGetThrowsWhenValueIsNotPresent(): void
    {
        $optional = Optional::empty();

        self::assertFalse($optional->isPresent());
        self::expectException(Exception\NoSuchElement::class);
        $optional->get();
    }

    public function testGetThrowsWhenCalledSeparately(): void
    {
        $optional = new Optional(self::VALUE);

        self::expectException(LogicException::class);
        $optional->get();
    }
}
