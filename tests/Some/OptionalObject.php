<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Some;

use PetrKnap\Optional\AbstractOptional;
use PetrKnap\Optional\Optional;
use PetrKnap\Optional\TypedOptional;

/**
 * @template T of object
 *
 * @extends Optional<T>
 */
abstract class OptionalObject extends Optional
{
    /** @use AbstractOptional<T> */
    use AbstractOptional;

    /**
     * @param T|null $value
     *
     * @return self<T>
     */
    protected static function createInstance(mixed $value): self
    {
        /** @var self<T> */
        return new class ($value) extends OptionalObject {
            protected static function isInstanceOfStatic(object $obj): bool
            {
                return $obj instanceof OptionalObject;
            }

            protected static function isSupported(mixed $value): bool
            {
                TypedOptional::triggerNotice(OptionalObject::class . ' does not check the instance of object.');
                return is_object($value);
            }
        };
    }
}
