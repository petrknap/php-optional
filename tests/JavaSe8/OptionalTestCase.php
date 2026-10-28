<?php

declare(strict_types=1);

namespace PetrKnap\Optional\JavaSe8;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

abstract class OptionalTestCase extends TestCase
{
    #[DataProvider('dataMatchesJavaBehavior')]
    final public function testMatchesJavaBehavior(string $assert): void
    {
        if (str_contains($assert, 'assertTrue(false)')) {
            self::expectNotToPerformAssertions();
        }

        eval($assert);
    }

    final public static function dataMatchesJavaBehavior(): iterable
    {
        $headers = 'namespace ' . __NAMESPACE__ . ';';
        foreach (static::getJavaLines() as $javaLine) {
            if (!str_starts_with($javaLine, '*') && str_contains($javaLine, 'assert (')) {
                yield $javaLine => [$headers . strtr($javaLine, [
                    'assert (' => '\\' . self::class . '::assertTrue(',
                    'Optional.<Alternative>' => 'AlternativeOption::',
                    'Optional.' => '\\' . static::getClassName() . '::',
                    'Record' => '\\' . stdClass::class,
                    '<Integer>' => '',
                    '.' => '->',
                    ' == ' => ' === ',
                    ' != ' => ' !== ',
                    '$v -> { }' => 'fn ($v) => null',
                    '$v -> {' => 'function ($v) { return ',
                    '$v -> ' => 'fn ($v) => ',
                    '() -> { }' => 'fn () => null',
                    '() -> {' => 'function () { return ',
                    '() -> ' => 'fn () => ',
                ])];
            }
        }
    }

    final protected static function loadJavaLines(string $path): array
    {
        return array_map(
            trim(...),
            explode("\n", file_get_contents($path)),
        );
    }

    protected static function getJavaLines(): array
    {
        return self::loadJavaLines(__DIR__ . '/OptionalTest.java');
    }

    /**
     * @return class-string<Optional>
     */
    abstract protected static function getClassName(): string;
}
