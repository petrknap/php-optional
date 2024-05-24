<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use Exception as SomeException;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class OptionalTest extends TestCase
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

    public function testMethodOfFalsableWorks(): void
    {
        self::assertEquals(
            Optional::ofNullable(null),
            Optional::ofFalsable(false),
        );
        self::assertEquals(
            Optional::ofNullable(self::VALUE),
            Optional::ofFalsable(self::VALUE),
        );
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
        $object1 = new \stdClass();
        $object1->property = self::VALUE;
        $object2 = new \stdClass();
        $object2->property = self::VALUE;
        $object3 = new \stdClass();
        $object3->property = self::OTHER;
        return [
            'equal (value)' => [Optional::of(self::VALUE), self::VALUE, true],
            'equal (optional)' => [Optional::of(self::VALUE), Optional::of(self::VALUE), true],
            'equal (object)' => [Optional::of($object1), $object2, true],
            'equal (optional<object>)' => [Optional::of($object1), Optional::of($object2), true],
            'equal (empty)' => [Optional::empty(), Optional::empty(), true],
            'not equal (value)' => [Optional::of(self::VALUE), self::OTHER, false],
            'not equal (optional)' => [Optional::of(self::VALUE), Optional::of(self::OTHER), false],
            'not equal (object)' => [Optional::of($object1), $object3, false],
            'not equal (optional<object>)' => [Optional::of($object1), Optional::of($object3), false],
            'not equal (empty-present)' => [Optional::empty(), Optional::of(self::VALUE), false],
            'not equal (present-empty)' => [Optional::of(self::VALUE), Optional::empty(), false],
        ];
    }

    #[DataProvider('dataMethodFilterWorks')]
    public function testMethodFilterWorks(Optional $optional, bool $expected): void
    {
        self::assertEquals(
            $expected ? $optional : $optional::empty(),
            $optional->filter(static fn (string $value): bool => $value === self::VALUE),
        );
    }

    public static function dataMethodFilterWorks(): array
    {
        return [
            self::VALUE => [Optional::of(self::VALUE), true],
            self::OTHER => [Optional::of(self::OTHER), false],
        ];
    }

    #[DataProvider('dataMethodFlatMapWorks')]
    public function testMethodFlatMapWorks(Optional $optional, Optional $expectedResult): void
    {
        self::assertTrue($expectedResult->equals($optional->flatMap(fn (string $v): Optional => Optional::of($v . 'x'))));
    }

    public static function dataMethodFlatMapWorks(): array
    {
        return self::makeDataSet([
            [Optional::of(self::VALUE . 'x')],
            [Optional::empty()],
        ]);
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
            [null, JavaSe8\NoSuchElementException::class],
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

    #[DataProvider('dataMethodMapWorks')]
    public function testMethodMapWorks(Optional $optional, mixed $expectedResult): void
    {
        self::assertTrue($expectedResult->equals($optional->map(fn (string $v): string => $v . 'x')));
    }

    public static function dataMethodMapWorks(): array
    {
        return self::makeDataSet([
            [OptionalString::of(self::VALUE . 'x')],
            [Optional::empty()],
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
