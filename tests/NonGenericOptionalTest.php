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

        Some\OptionalDataObject::empty();
        Some\OptionalDataObject::of($value);
        Some\OptionalDataObject::ofFalsable($value);
        Some\OptionalDataObject::ofNullable($value);
        Some\OptionalDataObject::ofSingle([$value]);
    }

    #[DataProvider('dataMethodsReturnsSelf')]
    public function testMethodsReturnsSelf(Some\OptionalDataObject $option): void
    {
        self::expectNotToPerformAssertions(); // it's checked natively by PHP

        $option->filter(static fn (): bool => true);
        $option->filter(static fn (): bool => false);
    }

    public static function dataMethodsReturnsSelf(): array
    {
        return [
            'empty' => [Some\OptionalDataObject::empty()],
            'some' => [Some\OptionalDataObject::of(new Some\DataObject())],
        ];
    }
}
