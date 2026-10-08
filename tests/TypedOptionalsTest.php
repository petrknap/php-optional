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
        self::assertInstanceOf($optionalClassName, Optional::of($value));
        self::assertInstanceOf($optionalClassName, Optional::ofNullable($value));
        self::assertInstanceOf($optionalClassName, TypedOptional::of($value, Optional::class));
    }

    public static function dataCouldBeCreated(): array
    {
        return [
            'array' => [Some\OptionalArray::class, []],
            'object' => [Some\OptionalObject::class, new stdClass(), ['object(stdClass)']],
            'object(stdClass)' => [Some\OptionalObject\OptionalStdClass::class, new stdClass(), ['object']],
            'string' => [Some\OptionalString::class, ''],
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
        $data = self::dataCouldBeCreated();

        foreach ($data as $supportedCase => [$optionalClassName, $_, $alsoSupportedCases]) {
            foreach ($data as $unsupportedCase => [$_, $value]) {
                if (in_array($unsupportedCase, [$supportedCase, ...($alsoSupportedCases ?? [])])) {
                    continue;
                }
                yield "({$supportedCase}) {$unsupportedCase}" => [$optionalClassName, $value];
            }
        }
    }

    public function testTwoEmptiesOfSameTypeAreEqual(): void
    {
        self::assertTrue(Some\OptionalString::empty()->equals(Some\OptionalString::empty()));
    }

    public function testTwoEmptiesOfDifferentTypesAreEqual(): void
    {
        self::assertTrue(Some\OptionalString::empty()->equals(Some\OptionalArray::empty()));
    }
}
