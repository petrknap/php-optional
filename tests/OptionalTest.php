<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use DomainException as SomeException;
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

    #[DataProvider('dataMethodOfFalsableWorks')]
    public function testMethodOfFalsableWorks(Optional $expectedOptional, mixed $value): void
    {
        self::assertEquals($expectedOptional, Optional::ofFalsable($value));
    }

    public static function dataMethodOfFalsableWorks(): array
    {
        return self::makeDataSet([
            [self::VALUE],
            [false],
        ]);
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
    public function testMethodOrElseThrowWorks(
        Optional $optional,
        null|string|callable $exceptionProvider,
        null|string $message,
        ?string $expectedValue,
        ?string $expectedException,
        ?string $expectedExceptionMessage
    ): void {
        if ($expectedException) {
            self::expectException($expectedException);
        }
        if ($expectedExceptionMessage) {
            self::expectExceptionMessage($expectedExceptionMessage);
        }
        self::assertSame($expectedValue, $optional->orElseThrow($exceptionProvider, $message));
    }

    public static function dataMethodOrElseThrowWorks(): iterable
    {
        $message = 'This is test!';
        $dataSet = self::makeDataSet([
            [null, null, self::VALUE, null, null],
            [null, null, null, SomeException::class, null],
        ]);
        foreach ($dataSet as $name => $data) {
            $data[4] = $data[4] === null ? null : Exception\CouldNotGetValueOfEmptyOptional::class;
            yield "{$name} + supplier(null)" => $data;
            $data[2] = $data[5] = $data[4] === null ? null : $message;
            yield "{$name} + supplier(null) + message" => $data;
        }
        $exceptionSupplier = static fn(string|null $message): SomeException => new SomeException($message ?? '');
        foreach ($dataSet as $name => $data) {
            $data[1] = $exceptionSupplier;
            yield "{$name} + supplier(callable)" => $data;
            $data[2] = $data[5] = $data[4] === null ? null : $message;
            yield "{$name} + supplier(callable) + message" => $data;
        }
        $exceptionSupplier = SomeException::class;
        foreach ($dataSet as $name => $data) {
            $data[1] = $exceptionSupplier;
            yield "{$name} + supplier(class name)" => $data;
            $data[2] = $data[5] = $data[4] === null ? null : $message;
            yield "{$name} + supplier(class name) + message" => $data;
        }
    }

    #[DataProvider('dataMethodToNullableWorks')]
    public function testMethodToNullableWorks(Optional $optional, mixed $expectedValue): void
    {
        self::assertSame($expectedValue, $optional->toNullable());
    }

    public static function dataMethodToNullableWorks(): array
    {
        return self::makeDataSet([
            [self::VALUE],
            [null],
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
