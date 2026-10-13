<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Some;

use PetrKnap\Optional\OptionalOverrides;
use PetrKnap\Optional\Optional as Base;

/**
 * @template-covariant T of mixed type of non-null value
 *
 * @extends Base<T>
 */
final class Optional extends Base
{
    /** @use OptionalOverrides<T> */
    use OptionalOverrides;
}
