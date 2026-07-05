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
            Some\OptionalResource::class,
            Some\OptionalResource::empty(),
        );
    }

    public function testUsesCorrectType()
    {
        self::assertInstanceOf(
            Some\OptionalResource\OptionalStream::class,
            Some\OptionalResource::of(fopen('php://memory', 'rw')),
        );
    }

    public function testEqualResourcesAreEqual(): void
    {
        $r = fopen('php://memory', 'rw');
        $a = Some\OptionalResource::of($r);
        $b = Some\OptionalResource::of($r);

        self::assertTrue($a->equals($b));
    }

    public function testDifferentResourcesAreNotEqual(): void
    {
        $a = Some\OptionalResource::of(fopen('php://memory', 'rw'));
        $b = Some\OptionalResource::of(fopen('php://memory', 'rw'));

        self::assertFalse($a->equals($b));
    }
}
