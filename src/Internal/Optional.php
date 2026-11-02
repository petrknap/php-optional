<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Internal;

use PetrKnap\Optional\Optional as AbstractOptional;
use PetrKnap\Optional\OptionalOverrides;

/**
 * @internal You should use {@see AbstractOptional}.
 *
 * @note This class should not use {@see OptionalOverrides}.
 *
 * @template-covariant T of mixed type of non-null value
 *
 * @extends AbstractOptional<T>
 */
final class Optional extends AbstractOptional
{
}
