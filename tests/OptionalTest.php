<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use DomainException as SomeException;
use Override;

final class OptionalTest extends OptionalTestCase
{
    public function testEqualsByReferenceWorks(): void
    {
        $object = $sameObject = new \stdClass();
        $equalObject = new \stdClass();

        self::assertTrue(Optional::of($object)->equals(Optional::of($sameObject), byReference: true));
        self::assertFalse(Optional::of($object)->equals(Optional::of($equalObject), byReference: true));
    }

    public function testIfPresentsElseWorks(): void
    {
        $consumerCalled = false;
        Optional::of(1)->ifPresent(
            function () use (&$consumerCalled): void {
                $consumerCalled = true;
            },
            else: self::fail(...),
        );
        self::assertTrue($consumerCalled);

        $elseCalled = false;
        Optional::empty()->ifPresent(
            self::fail(...),
            else: function () use (&$elseCalled): void {
                $elseCalled = true;
            },
        );
        self::assertTrue($elseCalled);
    }

    public function testOrElseThrowsCustomExceptionMessage(): void
    {
        self::expectExceptionMessage('test');
        Optional::empty()->orElseThrow(message: 'test');
    }

    public function testOrElseThrowsCustomExceptionClass(): void
    {
        self::expectException(SomeException::class);
        Optional::empty()->orElseThrow(SomeException::class);
    }

    public function testOrElseThrowsCustomException(): void
    {
        $expectedException = new SomeException();
        try {
            Optional::empty()->orElseThrow(static fn (): SomeException => $expectedException);
        } catch (SomeException $exception) {
            self::assertSame($expectedException, $exception);
        }
    }

    #[Override]
    public static function dataCustomFactoryWorks(): iterable
    {
        return [
            'ofFalsable' => [Optional::ofFalsable(...), 0, false, null],
            'ofSingle' => [Optional::ofSingle(...), [0], [], [1, 2]],
        ];
    }

    protected static function getClassName(): string
    {
        return Optional::class;
    }
}
