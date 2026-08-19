<?php

/**
 * This file is checked by PhpStan as production code and confirms correctness of PhpDocs
 */

declare(strict_types=1);

use PetrKnap\Optional\Optional;
use PetrKnap\Optional\Some\DataObject;
use PetrKnap\Optional\Some\OptionalArray;
use PetrKnap\Optional\Some\OptionalBool;

$check = (new class {
    /**
     * @param Optional<array<object>> $abstract
     * @param OptionalArray<array<object>> $concrete
     */
    public function covariantInputOptions(Optional $abstract, OptionalArray $concrete): void {}
    /**
     * @param Optional<array<mixed>> $abstract
     * @param OptionalArray<array<mixed>> $concrete
     */
    public function genericInputOptions(Optional $abstract = null, OptionalArray $concrete = null): void {}
    /**
     * @param Optional<bool> $abstract
     */
    public function nonGenericInputOptions(Optional $abstract = null, OptionalBool $concrete = null): void {}

    /**
     * @param array<mixed> $array
     */
    public function rawInputs(array $array = [], bool $bool = false): void {}
});

// ---------------------------------------------------------------------------------------------------------------------

// Factories
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
OptionalBool::of(false);
OptionalBool::of(null); // @phpstan-ignore argument.type
OptionalBool::of([]); // @phpstan-ignore argument.type
OptionalBool::ofNullable(false);
OptionalBool::ofNullable(null);
OptionalBool::ofNullable([]); // @phpstan-ignore argument.type

// Check return types of factory `of`
$check->genericInputOptions(
    abstract: Optional::of([false]),
    concrete: OptionalArray::of([false]),
);
$check->nonGenericInputOptions(
    abstract: Optional::of(false),
    concrete: OptionalBool::of(false),
);

// ---------------------------------------------------------------------------------------------------------------------

// Abstract options
$arrayOption = Optional::of([]);
$boolOption = Optional::of(false);

// Call all methods with generic arguments
// - filter
$arrayOption->filter(static fn (array $value): bool => true);
$arrayOption->filter(static fn (bool $value): bool => true); // @phpstan-ignore argument.type
$boolOption->filter(static fn (bool $value): bool => true);
$boolOption->filter(static fn (array $value): bool => true); // @phpstan-ignore argument.type
// - flatMap
$arrayOption->flatMap(static fn (array $value): Optional => $arrayOption);
$arrayOption->flatMap(static fn (bool $value): Optional => $arrayOption); // @phpstan-ignore argument.type
$boolOption->flatMap(static fn (bool $value): Optional => $boolOption);
$boolOption->flatMap(static fn (array $value): Optional => $boolOption); // @phpstan-ignore argument.type
// - ifPresent
$arrayOption->ifPresent(static fn (array $value): array => $value);
$arrayOption->ifPresent(static fn (bool $value): bool => $value); // @phpstan-ignore argument.type
$boolOption->ifPresent(static fn (bool $value): bool => $value);
$boolOption->ifPresent(static fn (array $value): array => $value); // @phpstan-ignore argument.type
// - map
$arrayOption->map(static fn (array $value): array => $value);
$arrayOption->map(static fn (bool $value): bool => $value); // @phpstan-ignore argument.type
$boolOption->map(static fn (bool $value): bool => $value);
$boolOption->map(static fn (array $value): array => $value); // @phpstan-ignore argument.type
// - orElse
$arrayOptionOrElse = $arrayOption->orElse([]);
$arrayOptionOrElseNull = $arrayOption->orElse(null);
$arrayOption->orElse(false); // @phpstan-ignore argument.type
$boolOptionOrElse = $boolOption->orElse(false);
$boolOptionOrElseNull = $boolOption->orElse(null);
$boolOption->orElse([]); // @phpstan-ignore argument.type
// - orElseGet
$arrayOptionOrElseGet = $arrayOption->orElseGet(static fn (): array => []);
$arrayOption->orElseGet(static fn (): bool => false); // @phpstan-ignore argument.type
$boolOptionOrElseGet = $boolOption->orElseGet(static fn (): bool => false);
$boolOption->orElseGet(static fn (): array => []); // @phpstan-ignore argument.type

// Re-map & filter it
$boolOptionMapped = $arrayOption->map(static fn (array $value): bool => false);
$boolOptionMappedFiltered = $boolOptionMapped->filter(static fn (bool $value): bool => true);
$boolOptionMapped->filter(static fn (array $value): bool => true); // @phpstan-ignore argument.type

// Check it
// - generic
$check->genericInputOptions(abstract: $arrayOption);
$check->nonGenericInputOptions(abstract: $arrayOption); // @phpstan-ignore argument.type
$check->rawInputs(array: $arrayOption->get());
$check->rawInputs(array: $arrayOption->orElseThrow());
$check->rawInputs(array: $arrayOptionOrElseGet);
$check->rawInputs(array: $arrayOptionOrElseNull); // @phpstan-ignore argument.type
$check->rawInputs(array: $arrayOptionOrElseNull ?? []);
$check->rawInputs(array: $arrayOptionOrElse);
// - non-generic
$check->genericInputOptions(abstract: $boolOption); // @phpstan-ignore argument.type
$check->nonGenericInputOptions(abstract: $boolOption);
$check->rawInputs(bool: $boolOption->get());
$check->rawInputs(bool: $boolOption->orElseThrow());
$check->rawInputs(bool: $boolOptionOrElseGet);
$check->rawInputs(bool: $boolOptionOrElseNull); // @phpstan-ignore argument.type
$check->rawInputs(bool: $boolOptionOrElseNull ?? false);
$check->rawInputs(bool: $boolOptionOrElse);
// - re-mapped & filtered
$check->genericInputOptions(abstract: $boolOptionMappedFiltered); // @phpstan-ignore argument.type
$check->nonGenericInputOptions(abstract: $boolOptionMappedFiltered);
$check->rawInputs(bool: $boolOptionMappedFiltered->get());

// ---------------------------------------------------------------------------------------------------------------------

// Concrete options
$arrayOption = OptionalArray::of([]);
$boolOption = OptionalBool::of(false);

// Call all methods with generic arguments
// - filter
$arrayOption->filter(static fn (array $value): bool => true);
$arrayOption->filter(static fn (bool $value): bool => true); // @phpstan-ignore argument.type
$boolOption->filter(static fn (bool $value): bool => true);
$boolOption->filter(static fn (array $value): bool => true); // @phpstan-ignore argument.type
// - flatMap
$arrayOption->flatMap(static fn (array $value): Optional => $arrayOption);
$arrayOption->flatMap(static fn (bool $value): Optional => $arrayOption); // @phpstan-ignore argument.type
$boolOption->flatMap(static fn (bool $value): Optional => $boolOption);
$boolOption->flatMap(static fn (array $value): Optional => $boolOption); // @phpstan-ignore argument.type
// - ifPresent
$arrayOption->ifPresent(static fn (array $value): array => $value);
$arrayOption->ifPresent(static fn (bool $value): bool => $value); // @phpstan-ignore argument.type
$boolOption->ifPresent(static fn (bool $value): bool => $value);
$boolOption->ifPresent(static fn (array $value): array => $value); // @phpstan-ignore argument.type
// - map
$arrayOption->map(static fn (array $value): array => $value);
$arrayOption->map(static fn (bool $value): bool => $value); // @phpstan-ignore argument.type
$boolOption->map(static fn (bool $value): bool => $value);
$boolOption->map(static fn (array $value): array => $value); // @phpstan-ignore argument.type
// - orElse
$arrayOptionOrElse = $arrayOption->orElse([]);
$arrayOptionOrElseNull = $arrayOption->orElse(null);
$arrayOption->orElse(false); // @phpstan-ignore argument.type
$boolOptionOrElse = $boolOption->orElse(false);
$boolOptionOrElseNull = $boolOption->orElse(null);
$boolOption->orElse([]); // @phpstan-ignore argument.type
// - orElseGet
$arrayOptionOrElseGet = $arrayOption->orElseGet(static fn (): array => []);
$arrayOption->orElseGet(static fn (): bool => false); // @phpstan-ignore argument.type
$boolOptionOrElseGet = $boolOption->orElseGet(static fn (): bool => false);
$boolOption->orElseGet(static fn (): array => []); // @phpstan-ignore argument.type

// Re-map & filter it
$boolOptionFlatMapped = $arrayOption->flatMap(static fn (array $value): OptionalBool => OptionalBool::of(false), empty: OptionalBool::empty());
$boolOptionFlatMappedFiltered = $boolOptionFlatMapped->filter(static fn (bool $value): bool => true);
$boolOptionFlatMapped->filter(static fn (array $value): bool => true); // @phpstan-ignore argument.type

// Check it
// - generic
$check->genericInputOptions(abstract: $arrayOption, concrete: $arrayOption);
$check->nonGenericInputOptions(abstract: $arrayOption); // @phpstan-ignore argument.type
$check->rawInputs(array: $arrayOption->get());
$check->rawInputs(array: $arrayOption->orElseThrow());
$check->rawInputs(array: $arrayOptionOrElseGet);
$check->rawInputs(array: $arrayOptionOrElseNull); // @phpstan-ignore argument.type
$check->rawInputs(array: $arrayOptionOrElseNull ?? []);
$check->rawInputs(array: $arrayOptionOrElse);
// - non-generic
$check->genericInputOptions(abstract: $boolOption); // @phpstan-ignore argument.type
$check->nonGenericInputOptions(abstract: $boolOption, concrete: $boolOption);
$check->rawInputs(bool: $boolOption->get());
$check->rawInputs(bool: $boolOption->orElseThrow());
$check->rawInputs(bool: $boolOptionOrElseGet);
$check->rawInputs(bool: $boolOptionOrElseNull); // @phpstan-ignore argument.type
$check->rawInputs(bool: $boolOptionOrElseNull ?? false);
$check->rawInputs(bool: $boolOptionOrElse);
// - re-mapped & filtered
$check->genericInputOptions(concrete: $boolOptionFlatMappedFiltered); // @phpstan-ignore argument.type
$check->nonGenericInputOptions(concrete: $boolOptionFlatMappedFiltered);
$check->rawInputs(bool: $boolOptionFlatMappedFiltered->get());

// ---------------------------------------------------------------------------------------------------------------------

// Create complexly generic option
/** @var array{array<mixed>, bool} $array */
$array = [[], false];
$arrayOption = OptionalArray::of($array);

// Call some methods with generic arguments
$arrayOptionFiltered = $arrayOption->filter(static fn (array $value): bool => true);
$arrayOption->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type
$arrayOption->orElse([[], false]);
$arrayOption->orElse([false, []]); // @phpstan-ignore argument.type

// Use filtered complexly generic option as input for function
$check->rawInputs(array: $arrayOptionFiltered->get()[0]);
$check->rawInputs(bool: $arrayOptionFiltered->get()[0]); // @phpstan-ignore argument.type
$check->rawInputs(array: $arrayOptionFiltered->get()[1]); // @phpstan-ignore argument.type
$check->rawInputs(bool: $arrayOptionFiltered->get()[1]);

// ---------------------------------------------------------------------------------------------------------------------

// Create covariant options
$stdClassArrayAbstractOption = Optional::of([new stdClass()]);
$stdClassArrayConcreteOption = OptionalArray::of([new stdClass()]);

// Pass covariant options as input argument
$check->covariantInputOptions(
    abstract: $stdClassArrayAbstractOption,
    concrete: $stdClassArrayConcreteOption,
);

// ---------------------------------------------------------------------------------------------------------------------
