<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use DomainException as SomeException;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

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

    #[DataProvider('dataMethodOfSingleWorks')]
    public function testMethodOfSingleWorks(Optional $expectedOptional, iterable $value): void
    {
        self::assertTrue(Optional::ofSingle($value)->equals($expectedOptional));
    }

    public static function dataMethodOfSingleWorks(): array
    {
        return self::makeDataSet([
            [[self::VALUE]],
            [[]],
        ]);
    }

    public function testMethodOfSingleThrowsOnMultipleValues(): void
    {
        self::expectException(InvalidArgumentException::class);

        Optional::ofSingle([self::VALUE, self::VALUE]);
    }

    #[DataProvider('dataMethodEqualsWorks')]
    public function testMethodEqualsWorks(Optional $optional, mixed $obj, bool|null $isStrict, bool $expectedResult): void
    {
        self::assertSame($expectedResult, match ($isStrict === null) {
            true => $optional->equals($obj),
            false => $optional->equals($obj, strict: $isStrict),
        });
    }

    public static function dataMethodEqualsWorks(): array
    {
        $object = new Some\DataObject(self::VALUE);
        $set = static function (string $key, Optional $optional, array $equals, bool|null $isStrict = null) use ($object): iterable {
            $sameObject = $object;
            $equalObject = new Some\DataObject(self::VALUE);
            $otherObject = new Some\DataObject(self::OTHER);
            $differentObject = new stdClass();
            $differentObject->value = self::VALUE;

            $set = [
                // non-optional
                'null' => null,
                'value' => self::VALUE,
                'other value' => self::OTHER,
                'same object' => $sameObject,
                'equal object' => $equalObject,
                'other object' => $otherObject,
                'different object' => $differentObject,
                // optional
                'optional (empty)' => Optional::empty(),
                'optional (value)' => Optional::of(self::VALUE),
                'optional (other value)' => Optional::of(self::OTHER),
                'optional (same object)' => Optional::of($sameObject),
                'optional (equal object)' => Optional::of($equalObject),
                'optional (other object)' => Optional::of($otherObject),
                'optional (different object)' => Optional::of($differentObject),
                // registered typed optional
                'registered typed optional (empty)' => OptionalString::empty(),
                'registered typed optional (value)' => OptionalString::of(self::VALUE),
                'registered typed optional (other value)' => OptionalString::of(self::OTHER),
                // unregistered typed optional
                'unregistered typed optional (empty)' => Some\OptionalDataObject::empty(),
                'unregistered typed optional (same object)' => Some\OptionalDataObject::of($sameObject),
                'unregistered typed optional (equal object)' => Some\OptionalDataObject::of($equalObject),
                'unregistered typed optional (other object)' => Some\OptionalDataObject::of($otherObject),
            ];
            foreach ($set as $name => $value) {
                $shouldEqual = in_array($name, $equals);
                yield sprintf(
                    '%s %s %s',
                    $key,
                    sprintf($shouldEqual ? '%ss to' : 'does not %s to', match ($isStrict) {
                        true => 'strictly equal',
                        false => 'loosely equal',
                        null => 'equal',
                    }),
                    $name,
                ) => [$optional, $value, $isStrict, $shouldEqual];
            }
        };

        return [
            ...$set(
                'optional (empty)', // ~ typeless (empty)
                Optional::empty(),
                ['null', 'optional (empty)', 'registered typed optional (empty)', 'unregistered typed optional (empty)'],
            ),
            ...$set(
                'optional (value)', // = registered typed optional (value)
                Optional::of(self::VALUE),
                ['value', 'optional (value)', 'registered typed optional (value)'],
            ),
            ...$set(
                'optional (object)', // ~ unregistered typed optional (object)
                Optional::of($object),
                ['same object', 'equal object', 'optional (same object)', 'optional (equal object)', 'unregistered typed optional (same object)', 'unregistered typed optional (equal object)'],
                false,
            ),
            ...$set(
                'optional (object)', // ~ unregistered typed optional (object)
                Optional::of($object),
                ['same object', 'optional (same object)', 'unregistered typed optional (same object)'],
                true,
            ),
            ...$set(
                'registered typed optional (empty)',
                OptionalString::empty(),
                ['null', 'registered typed optional (empty)'],
            ),
            ...$set(
                'registered typed optional (value)', // = optional (value)
                OptionalString::of(self::VALUE),
                ['value', 'optional (value)', 'registered typed optional (value)'],
            ),
            ...$set(
                'unregistered typed optional (empty)',
                Some\OptionalDataObject::empty(),
                ['null', 'unregistered typed optional (empty)'],
            ),
            ...$set(
                'unregistered typed optional (object)',
                Some\OptionalDataObject::of($object),
                ['same object', 'equal object', 'unregistered typed optional (same object)', 'unregistered typed optional (equal object)'],
                false,
            ),
            ...$set(
                'unregistered typed optional (object)',
                Some\OptionalDataObject::of($object),
                ['same object', 'unregistered typed optional (same object)'],
                true,
            ),
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
    public function testMethodIfPresentWorks(Optional $optional, string $expectedInvoked): void
    {
        $invoked = null;
        $optional->ifPresent(
            consumer: static function (string $value) use (&$invoked) {
                self::assertSame(self::VALUE, $value);
                $invoked = 'consumer';
            },
            else: static function (mixed ...$args) use (&$invoked) {
                self::assertSame([], $args);
                $invoked = 'else';
            },
        );

        self::assertSame($expectedInvoked, $invoked);
    }

    public static function dataMethodIfPresentWorks(): array
    {
        return self::makeDataSet([
            ['consumer'],
            ['else'],
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
