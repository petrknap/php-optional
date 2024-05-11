<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class OptionalTest extends TestCase
{
    private const VALUE = 'value';
    private const OTHER = 'other';

    #[DataProvider('dataMethodEqualsWorks')]
    public function testMethodEqualsWorks(Optional $optional, mixed $obj, bool $expectedResult): void
    {
        self::assertSame($expectedResult, $optional->equals($obj));
    }

    public static function dataMethodEqualsWorks(): array
    {
        $optionalValue = new Optional(self::VALUE);
        $optionalEmpty = Optional::empty();
        return [
            'equal (value)' => [$optionalValue, self::VALUE, true],
            'equal (optional)' => [$optionalValue, new Optional(self::VALUE), true],
            'equal (empty)' => [$optionalEmpty, Optional::empty(), true],
            'not equal (value)' => [$optionalValue, self::OTHER, false],
            'not equal (optional)' => [$optionalValue, new Optional(self::OTHER), false],
            'not equal (empty-present)' => [$optionalEmpty, $optionalValue, false],
            'not equal (present-empty)' => [$optionalValue, $optionalEmpty, false],
        ];
    }

    public function testMethodGetReturnsValueWhenValueIsPresent(): void
    {
        $optional = new Optional(self::VALUE);

        self::assertTrue($optional->isPresent());
        self::assertSame(self::VALUE, $optional->get());
    }

    public function testMethodGetThrowsWhenValueIsNotPresent(): void
    {
        $optional = Optional::empty();

        self::assertFalse($optional->isPresent());
        self::expectException(Exception\NoSuchElement::class);
        $optional->get();
    }

    public function testMethodGetThrowsWhenCalledSeparately(): void
    {
        $optional = new Optional(self::VALUE);

        self::expectException(LogicException::class);
        $optional->get();
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

    private static function makeDataSet(array $args): array
    {
        return [
            'present value' => [new Optional(self::VALUE), ...$args[0]],
            'not present value' => [Optional::empty(), ...$args[1]],
        ];
    }
}
