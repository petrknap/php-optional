<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Internal;

use PetrKnap\Optional\OptionalTestCase;

final class OptionalTest extends OptionalTestCase
{
    #[\Override]
    protected static function getClassName(): string
    {
        return Optional::class;
    }
}
