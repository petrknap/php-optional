<?php

declare(strict_types=1);

namespace PetrKnap\Optional\JavaSe9;

use PetrKnap\Optional\JavaSe8;

abstract class OptionalTestCase extends JavaSe8\OptionalTestCase
{
    #[\Override]
    protected static function getJavaLines(): array
    {
        return array_merge(
            parent::getJavaLines(),
            self::loadJavaLines(__DIR__ . '/OptionalTest.java'),
        );
    }

    #[\Override]
    protected static function getJava2PhpTranslations(): array
    {
        return array_merge(
            parent::getJava2PhpTranslations(),
            [
                '$v -> { }' => 'fn ($v) => null',
                '() -> { }' => 'fn () => null',
            ],
        );
    }
}
