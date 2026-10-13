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
        $javaLines = array_map(
            trim(...),
            explode("\n", file_get_contents(__DIR__ . '/OptionalTest.java')),
        );

        $headers = 'namespace ' . __NAMESPACE__ . ';';
        foreach ($javaLines as $javaLine) {
            if (!str_starts_with($javaLine, '*') && str_contains($javaLine, 'assert (')) {
                yield $javaLine => [$headers . strtr($javaLine, [
                    'assert (' => '\\' . self::class . '::assertTrue(',
                    'Optional.<Another>' => 'AnotherOption::',
                    'Optional.' => '\\' . static::getClassName() . '::',
                    'Record' => '\\' . stdClass::class,
                    '<Integer>' => '',
                    '.' => '->',
                    ' == ' => ' === ',
                    ' != ' => ' !== ',
                    '$v -> {' => 'function ($v) { return ',
                    '$v -> ' => 'fn ($v) => ',
                    '() -> {' => 'function () { return ',
                    '() -> ' => 'fn () => ',
                ])];
            }
        }
    }

    /**
     * @return class-string<Optional>
     */
    abstract protected static function getClassName(): string;
}
