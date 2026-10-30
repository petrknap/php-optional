<?php

declare(strict_types=1);

namespace PetrKnap\Optional\JavaSe16;

use PetrKnap\Optional\JavaSe11;

abstract class OptionalTestCase extends JavaSe11\OptionalTestCase
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
                'Record' => '\\' . Record::class,
            ],
        );
    }
}
