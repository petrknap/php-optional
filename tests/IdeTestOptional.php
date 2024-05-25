<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

/**
 * @extends OptionalObject<IdeTest>
 */
final class IdeTestOptional extends OptionalObject
{
    protected static function getInstanceOf(): string
    {
        return IdeTest::class;
    }
}
