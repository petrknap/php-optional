<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Exception;

use PetrKnap\Optional\JavaSe8\NoSuchElementException;

final class CouldNotGetValueOfEmptyOptional extends NoSuchElementException implements OptionalException
{
}
