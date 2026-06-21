<?php

/**
 * This file is checked by PhpStan as production code and confirms correctness of PhpDocs
 */

declare(strict_types=1);

use PetrKnap\Optional\Optional;
use PetrKnap\Optional\OptionalArray;
use PetrKnap\Optional\OptionalInt;
use PetrKnap\Optional\OptionalObject;
use PetrKnap\Optional\OptionalString;

$check = (new class {
    /**
     * @param Optional<object> $object
     */
    public function covariantInputOption(Optional $object): void {}
    /**
     * @param Optional<string>|null $string
     * @param Optional<int>|null $int
     */
    public function genericInputOptions(Optional $string = null, Optional $int = null): void {}
    public function nonGenericInputOptions(OptionalString $string = null, OptionalInt $int = null): void {}
    public function nonGenericInputs(string $string = '', int $int = 0): void {}
});

// ---------------------------------------------------------------------------------------------------------------------

// Call all factories
OptionalString::of('');
OptionalString::of(null); // @phpstan-ignore argument.type
OptionalString::of(false); // @phpstan-ignore argument.type
OptionalString::ofFalsable('');
OptionalString::ofFalsable(null); // @phpstan-ignore argument.type
OptionalString::ofFalsable(false);
OptionalString::ofNullable('');
OptionalString::ofNullable(null);
OptionalString::ofNullable(false); // @phpstan-ignore argument.type
OptionalString::ofSingle(['']);
OptionalString::ofSingle([null]); // @phpstan-ignore argument.type
OptionalString::ofSingle([false]); // @phpstan-ignore argument.type
OptionalString::ofSingle([]);

// Check sub-typed optional factory
OptionalObject::of(new stdClass());
OptionalObject::of(new class {
});
OptionalObject::of(''); // @phpstan-ignore argument.type, argument.templateType
OptionalObject\OptionalStdClass::of(new stdClass());
OptionalObject\OptionalStdClass::of(new class { // @phpstan-ignore argument.type
});
OptionalObject\OptionalStdClass::of(''); // @phpstan-ignore argument.type

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
$intOptionMapped = $stringOption->map(static fn (string $value): int => 0);
$intOptionMappedFiltered = $intOptionMapped->filter(static fn (int $value): bool => true);
$intOptionMapped->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type
$intOptionFlatMapped = $stringOption->flatMap(static fn (string $value): Optional => Optional::of(0));
$intOptionFlatMappedFiltered = $intOptionFlatMapped->filter(static fn (int $value): bool => true);
$intOptionFlatMapped->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type

// Use re-mapped filtered options as input for functions
$check->genericInputOptions(int: $intOptionMappedFiltered);
$check->nonGenericInputOptions(int: $intOptionMappedFiltered); // @phpstan-ignore argument.type
$check->nonGenericInputs(int: $intOptionMappedFiltered->get());
$check->genericInputOptions(int: $intOptionFlatMappedFiltered);
$check->nonGenericInputOptions(int: $intOptionFlatMappedFiltered); // @phpstan-ignore argument.type
$check->nonGenericInputs(int: $intOptionFlatMappedFiltered->get());

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
$intOptionMapped = $stringOption->map(static fn (string $value): int => 0);
$intOptionMappedFiltered = $intOptionMapped->filter(static fn (int $value): bool => true);
$intOptionMapped->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type
$intOptionFlatMapped = $stringOption->flatMap(static fn (string $value): OptionalInt => OptionalInt::of(0), empty: OptionalInt::empty());
$intOptionFlatMappedFiltered = $intOptionFlatMapped->filter(static fn (int $value): bool => true);
$intOptionFlatMapped->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type

// Use re-mapped filtered options as input for functions
$check->genericInputOptions(int: $intOptionMappedFiltered);
$check->nonGenericInputOptions(int: $intOptionMappedFiltered); // @phpstan-ignore argument.type
$check->nonGenericInputs(int: $intOptionMappedFiltered->get());
$check->genericInputOptions(int: $intOptionFlatMappedFiltered);
$check->nonGenericInputOptions(int: $intOptionFlatMappedFiltered);
$check->nonGenericInputs(int: $intOptionFlatMappedFiltered->get());

// ---------------------------------------------------------------------------------------------------------------------

// Create complexly generic option
/** @var array{string, int} $array */
$array = ['', 0];
$arrayOption = OptionalArray::of($array);

// Call some methods with generic arguments
$arrayOptionFiltered = $arrayOption->filter(static fn (array $value): bool => true);
$arrayOption->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type
$arrayOption->orElse(['1', 1]);
$arrayOption->orElse([1, '1']); // @phpstan-ignore argument.type

// Use filtered complexly generic option as input for function
$check->nonGenericInputs(string: $arrayOptionFiltered->get()[0]);
$check->nonGenericInputs(string: $arrayOptionFiltered->get()[1]); // @phpstan-ignore argument.type
$check->nonGenericInputs(int: $arrayOptionFiltered->get()[0]); // @phpstan-ignore argument.type
$check->nonGenericInputs(int: $arrayOptionFiltered->get()[1]);

// ---------------------------------------------------------------------------------------------------------------------

// Create covariant option
$stdClassOption = Optional::of(new stdClass());

// Pass covariant option as input argument
$check->covariantInputOption(object: $stdClassOption);

// ---------------------------------------------------------------------------------------------------------------------
