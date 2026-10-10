<?php

declare(strict_types=1);

namespace PetrKnap\Optional\JavaSe8;

use PetrKnap\Optional\Optional;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

abstract class OptionalTestCase extends TestCase
{
    #[DataProvider('dataCommonBehavior')]
    final public function testCommonBehavior(string $assert): void {
        eval($assert);
    }

    final public static function dataCommonBehavior(): iterable
    {
        $javaFile = Optional::ofFalsable(file_get_contents(__DIR__ . '/OptionalTest.java'))->orElseThrow();
        $javaLines = array_map(trim(...), explode("\n", $javaFile));
        foreach ($javaLines as $javaLine) {
            if (str_starts_with($javaLine, 'assert ')) {
                yield $javaLine => [strtr($javaLine, [
                    'assert' => self::class . '::assertTrue(',
                    'Optional.' => static::getClassName() . '::',
                    'Record' => stdClass::class,
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
