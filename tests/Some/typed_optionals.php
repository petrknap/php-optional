<?php

declare(strict_types=1);

namespace PetrKnap\Optional\Some;

use PetrKnap\Optional\TypedOptional;

TypedOptional::register(OptionalArray::class);
TypedOptional::register(OptionalBool::class);
TypedOptional::register(OptionalFloat::class);
TypedOptional::register(OptionalInt::class);
TypedOptional::register(OptionalObject::class);
TypedOptional::register(OptionalObject\OptionalStdClass::class);
TypedOptional::register(OptionalResource::class);
TypedOptional::register(OptionalResource\OptionalStream::class);
TypedOptional::register(OptionalString::class);
