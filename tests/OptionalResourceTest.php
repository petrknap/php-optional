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
            OptionalResource::of(tmpfile()),
        );
    }
}
