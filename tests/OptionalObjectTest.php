<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use Exception as SomeException;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use stdClass;

final class OptionalObjectTest extends TestCase
{
    public function testOptionalObjectIsOptionalObject(): void
    {
        self::assertInstanceOf(
            OptionalObject::class,
            OptionalObject::empty(),
        );
    }

    public function testEqualObjectsAreEqual(): void
    {
        $a = OptionalObject::of(new stdClass());
        $b = OptionalObject::of(new stdClass());

        self::assertTrue($a->equals($b));
    }

    public function testDifferentObjectsAreNotEqual(): void
    {
        $a = OptionalObject::of(new class () {
        });
        $b = OptionalObject::of(new class () {
        });

        self::assertFalse($a->equals($b));
    }
}
