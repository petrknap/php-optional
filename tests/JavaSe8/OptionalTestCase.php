<?php

declare(strict_types=1);

namespace PetrKnap\Optional\JavaSe8;

use PetrKnap\Optional\Optional;
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
        $javaLines = Optional::ofFalsable(file_get_contents(__DIR__ . '/OptionalTest.java'))
            ->map(static fn (string $javaFile): array => array_map(trim(...), explode("\n", $javaFile)))
            ->orElseThrow();
        foreach ($javaLines as $javaLine) {
            if (str_contains($javaLine, 'assert ')) {
                yield $javaLine => [strtr(strtr($javaLine, [
                    'assert ' => self::class . '::assertTrue(',
                    'Optional.' => static::getClassName() . '::',
                    'Record' => stdClass::class,
                    ' == ' => ' === ',
                    'value -> ' => 'fn ($value) => ',
                    '() -> ' => 'fn () => ',
                    '.' => '->',
                    ';' => ');',
                    '<Integer>' => '',
                    '<String>' => '',
                ]), [
                    'fn ($value) => { ' => 'fn ($value) => ',
                    'fn () => { ' => 'fn () => ',
                    '; }));' => ');',
                ])];
            }
        }
    }

    /**
     * @return class-string<Optional>
     */
    abstract protected static function getClassName(): string;
}
