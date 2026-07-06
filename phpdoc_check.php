<?php

/**
 * This file is checked by PhpStan as production code and confirms correctness of PhpDocs
 */

declare(strict_types=1);

use PetrKnap\Optional\Optional;
use PetrKnap\Optional\Some\DataObject;
use PetrKnap\Optional\Some\OptionalArray;
use PetrKnap\Optional\Some\OptionalInt;
use PetrKnap\Optional\Some\OptionalObject;
use PetrKnap\Optional\Some\OptionalString;

$check = (new class {
    /**
     * @param Optional<object> $object
     */
    public function covariantInputOption(Optional $object): void {}
    /**
     * @param Optional<string>|null $string
     * @param Optional<object>|null $object
     * @param Optional<array<object|string>>|null $array
     */
    public function genericInputOptions(Optional $string = null, Optional $object = null, Optional $array = null): void {}
    /**
     * @param OptionalObject<object>|null $object
     * @param OptionalArray<array<object|string>>|null $array
     */
    public function nonGenericInputOptions(OptionalString $string = null, OptionalObject $object = null, OptionalArray $array = null): void {}
    public function nonGenericInputs(string $string = '', object $object = new stdClass()): void {}
});

// ---------------------------------------------------------------------------------------------------------------------

// Call all factories
Optional::of('');
try {
    Optional::of(null);
    null; // @phpstan-ignore deadCode.unreachable
} catch(Throwable) {}
Optional::ofFalsable('');
try {
    Optional::ofFalsable(null);
    null; // @phpstan-ignore deadCode.unreachable
} catch(Throwable) {}
Optional::ofFalsable(false);
Optional::ofNullable('');
Optional::ofNullable(null);
Optional::ofNullable(false);
Optional::ofSingle(['']);
try {
    Optional::ofSingle([null]);
    null; // @phpstan-ignore deadCode.unreachable
} catch(Throwable) {}
Optional::ofSingle([]);

// Call main typed factories
OptionalArray::of([]);
OptionalArray::of(null); // @phpstan-ignore argument.type, argument.templateType
OptionalArray::of(false); // @phpstan-ignore argument.type, argument.templateType
OptionalArray::ofNullable([]);
OptionalArray::ofNullable(null); // @phpstan-ignore argument.templateType
OptionalArray::ofNullable(false); // @phpstan-ignore argument.type, argument.templateType
OptionalString::of('');
OptionalString::of(null); // @phpstan-ignore argument.type
OptionalString::of(false); // @phpstan-ignore argument.type
OptionalString::ofNullable('');
OptionalString::ofNullable(null);
OptionalString::ofNullable(false); // @phpstan-ignore argument.type

// Check return types of factory `of`
$stringOption = OptionalString::of('');
$objectOption = OptionalObject::of(new stdClass());
$arrayOption = OptionalArray::of(['', new stdClass()]);
$check->genericInputOptions($stringOption, $objectOption, $arrayOption);
$check->nonGenericInputOptions($stringOption, $objectOption, $arrayOption);

// ---------------------------------------------------------------------------------------------------------------------

// Create generic option
$stringOption = Optional::of('');

// Call all methods with generic arguments
$stringOption->filter(static fn (string $value): bool => true);
$stringOption->filter(static fn (int $value): bool => true); // @phpstan-ignore argument.type
$stringOption->flatMap(static fn (string $value): Optional => $stringOption);
$stringOption->flatMap(static fn (int $value): Optional => $stringOption); // @phpstan-ignore argument.type
$stringOption->ifPresent(static fn (string $value): string => $value);
$stringOption->ifPresent(static fn (int $value): int => $value); // @phpstan-ignore argument.type
$stringOption->map(static fn (string $value): string => $value);
$stringOption->map(static fn (int $value): int => $value); // @phpstan-ignore argument.type
$stringOptionOrElse = $stringOption->orElse('');
$stringOptionOrElseNull = $stringOption->orElse(null);
$stringOption->orElse(0); // @phpstan-ignore argument.type
$stringOptionOrElseGet = $stringOption->orElseGet(static fn (): string => '');
$stringOption->orElseGet(static fn (): int => 0); // @phpstan-ignore argument.type

// Use generic option as input for functions
$check->genericInputOptions(string: $stringOption);
$check->nonGenericInputOptions(string: $stringOption); // @phpstan-ignore argument.type
$check->nonGenericInputs(string: $stringOption->get());
$check->nonGenericInputs(string: $stringOption->orElseThrow());
$check->nonGenericInputs(string: $stringOptionOrElse);
$check->nonGenericInputs(string: $stringOptionOrElseNull); // @phpstan-ignore argument.type
$check->nonGenericInputs(string: $stringOptionOrElseNull ?? '');
$check->nonGenericInputs(string: $stringOptionOrElseGet);

// Re-map generic option & call filter on it to check new generic
$objectOptionMapped = $stringOption->map(static fn (string $value): object => new DataObject($value));
$objectOptionMappedFiltered = $objectOptionMapped->filter(static fn (object $value): bool => true);
$objectOptionMapped->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type
$objectOptionFlatMapped = $stringOption->flatMap(static fn (string $value): Optional => Optional::of(new DataObject($value)));
$objectOptionFlatMappedFiltered = $objectOptionFlatMapped->filter(static fn (object $value): bool => true);
$objectOptionFlatMapped->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type

// Use re-mapped filtered options as input for functions
$check->genericInputOptions(object: $objectOptionMappedFiltered);
$check->nonGenericInputOptions(object: $objectOptionMappedFiltered); // @phpstan-ignore argument.type
$check->nonGenericInputs(object: $objectOptionMappedFiltered->get());
$check->genericInputOptions(object: $objectOptionFlatMappedFiltered);
$check->nonGenericInputOptions(object: $objectOptionFlatMappedFiltered); // @phpstan-ignore argument.type
$check->nonGenericInputs(object: $objectOptionFlatMappedFiltered->get());

// ---------------------------------------------------------------------------------------------------------------------

// Create non-generic option
$stringOption = OptionalString::of('');

// Call all methods with generic arguments
$stringOption->filter(static fn (string $value): bool => true);
$stringOption->filter(static fn (int $value): bool => true); // @phpstan-ignore argument.type
$stringOption->flatMap(static fn (string $value): OptionalString => $stringOption);
$stringOption->flatMap(static fn (int $value): OptionalString => $stringOption); // @phpstan-ignore argument.type
$stringOption->ifPresent(static fn (string $value): string => $value);
$stringOption->ifPresent(static fn (int $value): int => $value); // @phpstan-ignore argument.type
$stringOption->map(static fn (string $value): string => $value);
$stringOption->map(static fn (int $value): int => $value); // @phpstan-ignore argument.type
$stringOptionOrElse = $stringOption->orElse('');
$stringOptionOrElseNull = $stringOption->orElse(null);
$stringOption->orElse(0); // @phpstan-ignore argument.type
$stringOptionOrElseGet = $stringOption->orElseGet(static fn (): string => '');
$stringOption->orElseGet(static fn (): int => 0); // @phpstan-ignore argument.type

// Use non-generic option as input for functions
$check->genericInputOptions(string: $stringOption);
$check->nonGenericInputOptions(string: $stringOption);
$check->nonGenericInputs(string: $stringOption->get());
$check->nonGenericInputs(string: $stringOption->orElseThrow());
$check->nonGenericInputs(string: $stringOptionOrElse);
$check->nonGenericInputs(string: $stringOptionOrElseNull); // @phpstan-ignore argument.type
$check->nonGenericInputs(string: $stringOptionOrElseNull ?? '');
$check->nonGenericInputs(string: $stringOptionOrElseGet);

// Re-map typed option & call filter on it to check new generic
$objectOptionMapped = $stringOption->map(static fn (string $value): object => new DataObject($value));
$objectOptionMappedFiltered = $objectOptionMapped->filter(static fn (object $value): bool => true);
$objectOptionMapped->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type
$objectOptionFlatMapped = $stringOption->flatMap(static fn (string $value): OptionalObject => OptionalObject::of(new DataObject($value)), empty: OptionalObject::empty());
$objectOptionFlatMappedFiltered = $objectOptionFlatMapped->filter(static fn (object $value): bool => true);
$objectOptionFlatMapped->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type

// Use re-mapped filtered options as input for functions
$check->genericInputOptions(object: $objectOptionMappedFiltered);
$check->nonGenericInputOptions(object: $objectOptionMappedFiltered); // @phpstan-ignore argument.type
$check->nonGenericInputs(object: $objectOptionMappedFiltered->get());
$check->genericInputOptions(object: $objectOptionFlatMappedFiltered);
$check->nonGenericInputOptions(object: $objectOptionFlatMappedFiltered);
$check->nonGenericInputs(object: $objectOptionFlatMappedFiltered->get());

// ---------------------------------------------------------------------------------------------------------------------

// Create complexly generic option
/** @var array{string, object} $array */
$array = ['', new DataObject()];
$arrayOption = OptionalArray::of($array);

// Call some methods with generic arguments
$arrayOptionFiltered = $arrayOption->filter(static fn (array $value): bool => true);
$arrayOption->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type
$arrayOption->orElse(['1', new stdClass()]);
$arrayOption->orElse([new stdClass(), '1']); // @phpstan-ignore argument.type

// Use filtered complexly generic option as input for function
$check->nonGenericInputs(string: $arrayOptionFiltered->get()[0]);
$check->nonGenericInputs(string: $arrayOptionFiltered->get()[1]); // @phpstan-ignore argument.type
$check->nonGenericInputs(object: $arrayOptionFiltered->get()[0]); // @phpstan-ignore argument.type
$check->nonGenericInputs(object: $arrayOptionFiltered->get()[1]);

// ---------------------------------------------------------------------------------------------------------------------

// Create covariant option
$stdClassOption = Optional::of(new stdClass());

// Pass covariant option as input argument
$check->covariantInputOption(object: $stdClassOption);

// ---------------------------------------------------------------------------------------------------------------------
