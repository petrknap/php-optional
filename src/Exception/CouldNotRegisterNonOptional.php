<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Exception;

use RuntimeException;

/**
 * @deprecated will be removed
 */
final class CouldNotRegisterNonOptional extends RuntimeException implements TypedOptionalException
{
    /**
     * @param class-string $className
     */
    public function __construct(
        private readonly string $className,
    ) {
        parent::__construct();
    }

    /**
     * @return class-string
     */
    public function getClassName(): string
    {
        return $this->className;
    }
}
