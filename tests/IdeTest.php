<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use PHPUnit\Framework\TestCase;

final class IdeTest extends TestCase
{
    public function testCheckThisInYourIde(): void
    {
        $optional = IdeTestOptional::of($this);

        if ($optional->isPresent()) {
            $optional->get()->tryIt();  # <--- HERE
        }

        $optional->orElse($this)->tryIt();  # <--- HERE
        $optional->orElseGet(fn (): self => $this)->tryIt();  # <--- HERE
        $optional->orElseThrow()->tryIt();  # <--- HERE

        $optional->filter(static fn (): bool => true)->orElseThrow()->tryIt();  # <--- HERE

        Optional::of(0)->flatMap(fn (): IdeTestOptional => IdeTestOptional::of($this))->orElseThrow()->tryIt();  # <--- HERE @todo fix it
        Optional::of(0)->map(fn (): IdeTest => $this)->orElseThrow()->tryIt();  # <--- HERE @todo fix it

        self::markTestSkipped('Try placing the cursor over each `tryIt` call.');
    }

    /**
     * It works.
     */
    public function tryIt(): void
    {
    }
}
