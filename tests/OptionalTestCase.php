<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;

abstract class OptionalTestCase extends JavaSe8\OptionalTestCase
{
    final public static function dataOptions(): array
    {
        $class = static::getClassName();
        return [
            'present' => [$class::of(0)],
            'empty' => [$class::empty()],
        ];
    }

    #[DataProvider('dataFactoryReturnsSelf')]
    final public function testFactoryReturnsSelf(callable $factory, array $args): void
    {
        self::assertInstanceOf(static::getClassName(), $factory(...$args));
    }

    final public static function dataFactoryReturnsSelf(): iterable
    {
        $data = [];
        foreach (static::dataCustomFactoryWorks() as $key => [$factory, $present, $empty]) {
            $data[$key . ' (present)'] = [$factory, [$present]];
            $data[$key . ' (empty)'] = [$factory, [$empty]];
        }
        $class = static::getClassName();
        return [
            ...$data,
            'empty (empty)' => [$class::empty(...), []],
            'of (present)' => [$class::of(...), [0]],
            'ofNullable (present)' => [$class::ofNullable(...), [0]],
            'ofNullable (empty)' => [$class::ofNullable(...), [null]],
        ];
    }

    #[DataProvider('dataOptions')]
    final public function testFilterReturnsSelf(Optional $option): void
    {
        self::assertInstanceOf(static::getClassName(), $option->filter(static fn (): bool => true));
        self::assertInstanceOf(static::getClassName(), $option->filter(static fn (): bool => false));
    }

    #[DataProvider('dataOptions')]
    final public function testMapReturnsSelf(Optional $option): void
    {
        self::assertInstanceOf(static::getClassName(), $option->map(static fn (int $v): int => $v));
    }

    #[DataProvider('dataCustomFactoryWorks')]
    final public function testCustomFactoryWorks(callable $factory, mixed $present, mixed $empty, mixed $wrong): void
    {
        $presentOption = $factory($present);
        self::assertInstanceOf(static::getClassName(), $presentOption);
        self::assertTrue($presentOption->isPresent());

        $emptyOption = $factory($empty);
        self::assertInstanceOf(static::getClassName(), $emptyOption);
        self::assertFalse($emptyOption->isPresent());

        self::expectException(InvalidArgumentException::class);
        $factory($wrong);
    }

    /**
     * @note {@see \Override} it if you implement custom factory
     *
     * @phpstan-type TCaseName non-empty-string
     * @phpstan-type TFactory callable
     * @phpstan-type TPresentValue mixed
     * @phpstan-type TEmptyValue mixed
     * @phpstan-type TWrongValue mixed
     *
     * @returns array<TCaseName, array{TFactory, TPresentValue, TEmptyValue, TWrongValue}>
     */
    public static function dataCustomFactoryWorks(): iterable
    {
        $skipMessage = static::getClassName() . ' does not have any custom factory';
        return [
            'customFactory' => [self::markTestSkipped(...), $skipMessage, $skipMessage, $skipMessage],
        ];
    }

    /**
     * @return class-string<Optional>
     */
    abstract protected static function getClassName(): string;
}
