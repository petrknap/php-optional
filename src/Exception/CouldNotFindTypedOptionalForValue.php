<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Exception;

use RuntimeException;

/**
 * @deprecated will be removed
 */
final class CouldNotFindTypedOptionalForValue extends RuntimeException implements TypedOptionalException
{
    public function __construct(
        private readonly mixed $value,
    ) {
        parent::__construct();
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}
