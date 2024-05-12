<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use Exception as SomeException;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class OptionalTest extends TestCase
{
    private const VALUE = 'value';
    private const OTHER = 'other';

    public function testMethodEmptyReturnsEmptyOptional(): void
    {
        self::assertFalse(Optional::empty()->isPresent());
    }

    public function testMethodOfReturnsOptionalWithValue(): void
    {
        $optional = Optional::of(self::VALUE);

        self::assertTrue($optional->isPresent());
        self::assertSame(self::VALUE, $optional->get());
    }

    public function testMethodOfThrowsWhenCalledWithNull(): void
    {
        self::expectException(LogicException::class);
        Optional::of(null);
    }

    #[DataProvider('dataMethodOfNullableWorks')]
    public function testMethodOfNullableWorks(Optional $expectedOptional, mixed $value): void
    {
        self::assertTrue(Optional::ofNullable($value)->equals($expectedOptional));
    }

    public static function dataMethodOfNullableWorks(): array
    {
        return self::makeDataSet([
            [self::VALUE],
            [null],
        ]);
    }

    #[DataProvider('dataMethodEqualsWorks')]
    public function testMethodEqualsWorks(Optional $optional, mixed $obj, bool $expectedResult): void
    {
        self::assertSame($expectedResult, $optional->equals($obj));
    }

    public static function dataMethodEqualsWorks(): array
    {
        $optionalValue = Optional::of(self::VALUE);
        $optionalEmpty = Optional::empty();
        return [
            'equal (value)' => [$optionalValue, self::VALUE, true],
            'equal (optional)' => [$optionalValue, Optional::of(self::VALUE), true],
            'equal (empty)' => [$optionalEmpty, Optional::empty(), true],
            'not equal (value)' => [$optionalValue, self::OTHER, false],
            'not equal (optional)' => [$optionalValue, Optional::of(self::OTHER), false],
            'not equal (empty-present)' => [$optionalEmpty, $optionalValue, false],
            'not equal (present-empty)' => [$optionalValue, $optionalEmpty, false],
        ];
    }

    #[DataProvider('dataMethodGetWorks')]
    public function testMethodGetWorks(Optional $optional, ?string $expectedValue, ?string $expectedException): void
    {
        if ($expectedException !== null) {
            self::expectException($expectedException);
        }
        self::assertSame($expectedValue, $optional->get());
    }

    public static function dataMethodGetWorks(): array
    {
        return self::makeDataSet([
            [self::VALUE, null],
            [null, Exception\NoSuchElement::class],
        ]);
    }

    #[DataProvider('dataMethodIfPresentWorks')]
    public function testMethodIfPresentWorks(Optional $optional, bool $expectedInvoke): void
    {
        $invoked = false;
        $optional->ifPresent(static function (string $value) use (&$invoked) {
            self::assertSame(self::VALUE, $value);
            $invoked = true;
        });

        self::assertSame($expectedInvoke, $invoked);
    }

    public static function dataMethodIfPresentWorks(): array
    {
        return self::makeDataSet([
            [true],
            [false],
        ]);
    }

    #[DataProvider('dataMethodIsPresentWorks')]
    public function testMethodIsPresentWorks(Optional $optional, bool $expectedReturn): void
    {
        self::assertSame($expectedReturn, $optional->isPresent());
    }

    public static function dataMethodIsPresentWorks(): array
    {
        return self::makeDataSet([
            [true],
            [false],
        ]);
    }

    #[DataProvider('dataMethodOrElseWorks')]
    public function testMethodOrElseWorks(Optional $optional, string $expectedValue): void
    {
        self::assertSame($expectedValue, $optional->orElse(self::OTHER));
    }

    public static function dataMethodOrElseWorks(): array
    {
        return self::makeDataSet([
            [self::VALUE],
            [self::OTHER],
        ]);
    }

    #[DataProvider('dataMethodOrElseGetWorks')]
    public function testMethodOrElseGetWorks(Optional $optional, string $expectedValue): void
    {
        self::assertSame($expectedValue, $optional->orElseGet(static fn(): string => self::OTHER));
    }

    public static function dataMethodOrElseGetWorks(): array
    {
        return self::makeDataSet([
            [self::VALUE],
            [self::OTHER],
        ]);
    }

    #[DataProvider('dataMethodOrElseThrowWorks')]
    public function testMethodOrElseThrowWorks(Optional $optional, ?string $expectedValue, ?string $expectedException): void
    {
        if ($expectedException) {
            self::expectException($expectedException);
        }
        self::assertSame($expectedValue, $optional->orElseThrow(static fn(): SomeException => new SomeException()));
    }

    public static function dataMethodOrElseThrowWorks(): array
    {
        return self::makeDataSet([
            [self::VALUE, null],
            [null, SomeException::class],
        ]);
    }

    private static function makeDataSet(array $args): array
    {
        return [
            'present value' => [Optional::of(self::VALUE), ...$args[0]],
            'not present value' => [Optional::empty(), ...$args[1]],
        ];
    }
}
