<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

final class TypedOptionalsTest extends TestCase
{
    /**
     * @param class-string<Optional> $optionalClassName
     */
    #[DataProvider('dataCouldBeCreated')]
    public function testCouldBeCreated(string $optionalClassName, mixed $value): void
    {
        self::assertInstanceOf($optionalClassName, $optionalClassName::empty());
        self::assertInstanceOf($optionalClassName, $optionalClassName::of($value));
        self::assertInstanceOf($optionalClassName, $optionalClassName::ofNullable($value));
        self::assertInstanceOf($optionalClassName, $optionalClassName::ofNullable(null));
        self::assertInstanceOf($optionalClassName, TypedOptional::of($value, Optional::class));
    }

    public static function dataCouldBeCreated(): array
    {
        return [
            // Scalars
            'bool' => [OptionalBool::class, true],
            'float' => [OptionalFloat::class, .1],
            'int' => [OptionalInt::class, 1],
            'string' => [OptionalString::class, ''],
            // Non-scalars
            'array' => [OptionalArray::class, []],
            'object' => [OptionalObject::class, new stdClass(), ['object(stdClass)']],
            'resource' => [OptionalResource::class, fopen('php://memory', 'rw'), ['resource(stream)']],
            // Objects
            'object(stdClass)' => [OptionalObject\OptionalStdClass::class, new stdClass(), ['object']],
            // Resources
            'resource(stream)' => [OptionalResource\OptionalStream::class, fopen('php://memory', 'rw'), ['resource']],
        ];
    }

    /**
     * @param class-string<Optional> $optionalClassName
     */
    #[DataProvider('dataCouldNotBeCreatedWithWrongType')]
    public function testCouldNotBeCreatedWithWrongType(string $optionalClassName, mixed $value): void
    {
        self::expectException(InvalidArgumentException::class);
        $optionalClassName::of($value);
    }

    public static function dataCouldNotBeCreatedWithWrongType(): iterable
    {
        $supportedValues = self::dataCouldBeCreated();

        foreach ($supportedValues as $supportedCase => [$optionalClassName, $_, $alsoSupportedCases]) {
            foreach ($supportedValues as $unsupportedCase => [$_, $value]) {
                if (in_array($unsupportedCase, [$supportedCase, ...($alsoSupportedCases ?? [])])) {
                    continue;
                }
                yield "({$supportedCase}) {$unsupportedCase}" => [$optionalClassName, $value];
            }
        }
    }

    public function testTwoEmptiesOfSameTypeAreEqual(): void
    {
        self::assertTrue(OptionalString::empty()->equals(OptionalString::empty()));
    }

    public function testTwoEmptiesOfDifferentTypesAreNotEqual(): void
    {
        self::assertFalse(OptionalString::empty()->equals(OptionalBool::empty()));
    }
}
