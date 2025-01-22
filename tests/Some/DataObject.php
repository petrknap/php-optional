<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Some;

final class DataObject
{
    public function __construct(
        public readonly string|null $value = null,
    ) {
    }

    /**
     * It works!
     *
     * @see IdeTest
     */
    public function tryIt(): void
    {
    }
}
