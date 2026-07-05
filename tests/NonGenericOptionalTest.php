<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NonGenericOptionalTest extends TestCase
{
    public function testFactoriesReturnsSelf(): void
    {
        self::expectNotToPerformAssertions(); // it's checked natively by PHP

        $value = new Some\DataObject();

        Some\OptionalObject\OptionalDataObject::empty();
        Some\OptionalObject\OptionalDataObject::of($value);
        Some\OptionalObject\OptionalDataObject::ofFalsable($value);
        Some\OptionalObject\OptionalDataObject::ofNullable($value);
        Some\OptionalObject\OptionalDataObject::ofSingle([$value]);
    }

    #[DataProvider('dataMethodsReturnsSelf')]
    public function testMethodsReturnsSelf(Some\OptionalObject\OptionalDataObject $option): void
    {
        self::expectNotToPerformAssertions(); // it's checked natively by PHP

        $option->filter(static fn (): bool => true);
        $option->filter(static fn (): bool => false);
    }

    public static function dataMethodsReturnsSelf(): array
    {
        return [
            'empty' => [Some\OptionalObject\OptionalDataObject::empty()],
            'some' => [Some\OptionalObject\OptionalDataObject::of(new Some\DataObject())],
        ];
    }
}
