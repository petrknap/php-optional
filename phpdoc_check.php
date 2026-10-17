<?php

/**
 * This file is checked by PhpStan as production code and confirms correctness of PhpDocs
 */

declare(strict_types=1);

use PetrKnap\Optional\Optional;
use PetrKnap\Optional\Some;

$check = (new class {
    /**
     * @param Optional<array<object>> $abstract
     * @param Some\Optional<array<object>> $concrete
     */
    public function covariantInputOptions(Optional $abstract, Some\Optional $concrete): void {}
    /**
     * @param Optional<string> $abstract
     * @param Some\Optional<string> $concrete
     */
    public function genericInputOptions(Optional $abstract = null, Some\Optional $concrete = null): void {}
    public function rawInputs(int $int = 0, string $string = ''): void {}
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
Some\Optional::of('');
try {
    Some\Optional::of(null);
    null; // @phpstan-ignore deadCode.unreachable
} catch(Throwable) {}
Some\Optional::ofFalsable('');
try {
    Some\Optional::ofFalsable(null);
    null; // @phpstan-ignore deadCode.unreachable
} catch(Throwable) {}
Some\Optional::ofFalsable(false);
Some\Optional::ofNullable('');
Some\Optional::ofNullable(null);
Some\Optional::ofNullable(false);
Some\Optional::ofSingle(['']);
try {
    Some\Optional::ofSingle([null]);
    null; // @phpstan-ignore deadCode.unreachable
} catch(Throwable) {}
Some\Optional::ofSingle([]);

// Check return types of factory `of`
$check->genericInputOptions(
    abstract: Optional::of(''),
    concrete: Some\Optional::of(''),
);

// ---------------------------------------------------------------------------------------------------------------------

// Check equals(byReference)
$option = $objectOption = $scalarOption = Optional::empty();
/**
 * @var Optional<object> $objectOption
 * @var Optional<scalar> $scalarOption
 */
$objectOption->equals($option);
$objectOption->equals($option, byReference: false);
$objectOption->equals($option, byReference: true);
$scalarOption->equals($option);
$scalarOption->equals($option, byReference: false);
$scalarOption->equals($option, byReference: true); // @phpstan-ignore argument.type
$option->equals($option, byReference: true);
$option->equals($objectOption, byReference: true);
$option->equals($scalarOption, byReference: true); // @phpstan-ignore argument.type

// ---------------------------------------------------------------------------------------------------------------------

// Options
$abstract = Optional::of(0);
$concrete = Some\Optional::of(0);

// Call all methods with generic arguments
// - filter
$abstract->filter(static fn (int $value): bool => true);
$abstract->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type
$concrete->filter(static fn (int $value): bool => true);
$concrete->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type
// - flatMap
$abstractFlatMapped = $abstract->flatMap(static fn (int $value): Some\Optional => Some\Optional::of(''));
$abstract->flatMap(static fn (string $value): Some\Optional => Some\Optional::of(1)); // @phpstan-ignore argument.type
$concreteFlatMapped = $concrete->flatMap(static fn (int $value): Optional => Optional::of(''));
$concrete->flatMap(static fn (string $value): Optional => Optional::of(1)); // @phpstan-ignore argument.type
// - ifPresent
$abstract->ifPresent(static fn (int $value): int => $value);
$abstract->ifPresent(static fn (string $value): string => $value); // @phpstan-ignore argument.type
$concrete->ifPresent(static fn (int $value): int => $value);
$concrete->ifPresent(static fn (string $value): string => $value); // @phpstan-ignore argument.type
// - map
$abstractMapped = $abstract->map(static fn (int $value): string => '');
$abstract->map(static fn (string $value): int => 1); // @phpstan-ignore argument.type
$concreteMapped = $concrete->map(static fn (int $value): string => '');
$concrete->map(static fn (string $value): int => 1); // @phpstan-ignore argument.type
// - orElse
$abstractOrElse = $abstract->orElse(0);
$abstractOrElseNull = $abstract->orElse(null);
$abstract->orElse(''); // @phpstan-ignore argument.type
$concreteOrElse = $concrete->orElse(0);
$concreteOrElseNull = $concrete->orElse(null);
$concrete->orElse(''); // @phpstan-ignore argument.type
// - orElseGet
$abstractOrElseGet = $abstract->orElseGet(static fn (): int => 0);
$abstract->orElseGet(static fn (): string => ''); // @phpstan-ignore argument.type
$concreteOrElseGet = $concrete->orElseGet(static fn (): int => 0);
$concrete->orElseGet(static fn (): string => ''); // @phpstan-ignore argument.type

// Check it
// - abstract
$check->genericInputOptions(abstract: $abstract); // @phpstan-ignore argument.type
$check->rawInputs(int: $abstract->get());
$check->rawInputs(int: $abstractOrElse);
$check->rawInputs(int: $abstractOrElseGet);
$check->rawInputs(int: $abstractOrElseNull); // @phpstan-ignore argument.type
$check->rawInputs(int: $abstractOrElseNull ?? 0);
$check->rawInputs(string: $abstractMapped->orElseThrow());
$check->rawInputs(string: $abstractFlatMapped->orElseThrow());
// - concrete
$check->genericInputOptions(concrete: $concrete); // @phpstan-ignore argument.type
$check->rawInputs(int: $concrete->get());
$check->rawInputs(int: $concreteOrElse);
$check->rawInputs(int: $concreteOrElseGet);
$check->rawInputs(int: $concreteOrElseNull); // @phpstan-ignore argument.type
$check->rawInputs(int: $concreteOrElseNull ?? 0);
$check->rawInputs(string: $concreteMapped->orElseThrow());
$check->rawInputs(string: $concreteFlatMapped->orElseThrow());

// ---------------------------------------------------------------------------------------------------------------------

// Create complexly generic option
/** @var array{int, string} $array */
$array = [0, ''];
$arrayOption = Optional::of($array);

// Call some methods with generic arguments
$arrayOptionFiltered = $arrayOption->filter(static fn (array $value): bool => true);
$arrayOption->filter(static fn (string $value): bool => true); // @phpstan-ignore argument.type
$arrayOption->orElse([0, '']);
$arrayOption->orElse(['', 0]); // @phpstan-ignore argument.type

// Use filtered complexly generic option as input for function
$check->rawInputs(int: $arrayOptionFiltered->get()[0]);
$check->rawInputs(string: $arrayOptionFiltered->get()[0]); // @phpstan-ignore argument.type
$check->rawInputs(int: $arrayOptionFiltered->get()[1]); // @phpstan-ignore argument.type
$check->rawInputs(string: $arrayOptionFiltered->get()[1]);

// ---------------------------------------------------------------------------------------------------------------------

// Create covariant options
$stdClassArrayAbstractOption = Optional::of([new stdClass()]);
$stdClassArrayConcreteOption = Some\Optional::of([new stdClass()]);

// Pass covariant options as input argument
$check->covariantInputOptions(
    abstract: $stdClassArrayAbstractOption,
    concrete: $stdClassArrayConcreteOption,
);

// ---------------------------------------------------------------------------------------------------------------------
