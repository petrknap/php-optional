<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use stdClass;

final class OptionalResourceTest extends TestCase
{
    public function testOptionalResourceIsOptionalResource(): void
    {
        self::assertInstanceOf(
            OptionalResource::class,
            OptionalResource::empty(),
        );
    }
}
