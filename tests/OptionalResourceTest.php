<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use stdClass;

final class OptionalResourceTest extends TestCase
{
    public function testIsCorrectType(): void
    {
        self::assertInstanceOf(
            OptionalResource::class,
            OptionalResource::empty(),
        );
    }

    public function testUsesCorrectType()
    {
        self::assertInstanceOf(
            OptionalResource\OptionalStream::class,
            OptionalResource::of(fopen('php://memory', 'rw')),
        );
    }

    public function testEqualResourcesAreEqual(): void
    {
        $r = fopen('php://memory', 'rw');
        $a = OptionalResource::of($r);
        $b = OptionalResource::of($r);

        self::assertTrue($a->equals($b));
    }

    public function testDifferentResourcesAreNotEqual(): void
    {
        $a = OptionalResource::of(fopen('php://memory', 'rw'));
        $b = OptionalResource::of(fopen('php://memory', 'rw'));

        self::assertFalse($a->equals($b));
    }
}
