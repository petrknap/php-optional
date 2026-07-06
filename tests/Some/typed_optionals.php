<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Some;

use PetrKnap\Optional\TypedOptional;

// @note: OptionalObject\OptionalDataObject is not registered for testing purposes

TypedOptional::register(OptionalArray::class);
TypedOptional::register(OptionalObject::class);
TypedOptional::register(OptionalObject\OptionalStdClass::class);
TypedOptional::register(OptionalString::class);
