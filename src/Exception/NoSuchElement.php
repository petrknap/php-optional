<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Exception;

use RuntimeException;

final class NoSuchElement extends RuntimeException implements OptionalException
{
}
