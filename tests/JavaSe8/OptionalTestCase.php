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
        eval($assert);
    }

    final public static function dataMatchesJavaBehavior(): iterable
    {
        $javaLines = Optional::ofFalsable(file_get_contents(__DIR__ . '/OptionalTest.java'))
            ->map(static fn (string $javaFile): array => array_map(trim(...), explode("\n", $javaFile)))
            ->orElseThrow();
        foreach ($javaLines as $javaLine) {
            if (str_starts_with($javaLine, 'assert ')) {
                yield $javaLine => [strtr($javaLine, [
                    'assert' => self::class . '::assertTrue(',
                    'Optional.' => static::getClassName() . '::',
                    'Record' => stdClass::class,
                    ' == ' => ' === ',
                    '_ -> ' => 'fn () => ',
                    '.' => '->',
                    ';' => ');',
                ])];
            }
        }
    }

    /**
     * @return class-string<Optional>
     */
    abstract protected static function getClassName(): string;
}
