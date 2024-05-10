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

    private static function makeDataSet(array $args): array
    {
        return [
            'present value' => [new Optional(self::VALUE), ...$args[0]],
            'not present value' => [Optional::empty(), ...$args[1]],
        ];
    }
}
