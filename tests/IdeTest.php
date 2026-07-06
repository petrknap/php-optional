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
        $optional = Some\OptionalObject\OptionalDataObject::of($instance);

        if ($optional->isPresent()) {
            $optional->get()->tryIt();  # <--- HERE
        }

        Optional::empty()->orElse($instance)->tryIt();  # <--- HERE
        Optional::empty()->orElseGet(static fn (): Some\DataObject => $instance)->tryIt();  # <--- HERE
        $optional->orElseThrow()->tryIt();  # <--- HERE

        $optional->filter(static fn (): bool => true)->orElseThrow()->tryIt();  # <--- HERE

        Optional::of(0)->flatMap(static fn (): Some\OptionalObject\OptionalDataObject => $optional)->orElseThrow()->tryIt();  # <--- HERE
        Optional::of(0)->map(static fn (): Some\DataObject => $instance)->orElseThrow()->tryIt();  # <--- HERE
    }
}
