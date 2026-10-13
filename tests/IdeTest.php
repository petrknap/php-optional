<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use PHPUnit\Framework\TestCase;

final class IdeTest extends TestCase
{
    /**
     * @note Try placing the cursor over each `tryIt` call.
     */
    public function testCheckThisInYourIde(): void
    {
        self::expectNotToPerformAssertions();

        $instance = new Some\DataObject();
        $option = Optional::of($instance);

        if ($option->isPresent()) {
            $option->get()->tryIt();  # <--- HERE
        }

        Optional::empty()->orElse($instance)->tryIt();  # <--- HERE
        Optional::empty()->orElseGet(static fn (): Some\DataObject => $instance)->tryIt();  # <--- HERE
        $option->orElseThrow()->tryIt();  # <--- HERE

        $option->filter(static fn (): bool => true)->orElseThrow()->tryIt();  # <--- HERE

        Optional::of(0)->flatMap(static fn (): Optional => $option)->orElseThrow()->tryIt();  # <--- HERE
        Optional::of(0)->map(static fn (): Some\DataObject => $instance)->orElseThrow()->tryIt();  # <--- HERE
    }
}
