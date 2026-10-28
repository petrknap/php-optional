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
}
