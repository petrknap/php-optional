# Optional (like in Java Platform SE 8 but in PHP)

> A container object which may or may not contain a non-null value. If a value is present, `isPresent()` will return `true` and `get()` will return the value.
>
> Additional methods that depend on the presence or absence of a contained value are provided, such as `orElse()` (return a default value if value not present) and `ifPresent()` (execute a block of code if the value is present).
>
> This is a [value-based](https://docs.oracle.com/javase/8/docs/api/java/lang/doc-files/ValueBased.html) class; use of identity-sensitive operations (including reference equality (==), identity hash code, or synchronization) on instances of Optional may have unpredictable results and should be avoided.
>
> --
> [Optional (Java Platform SE 8)](https://docs.oracle.com/javase/8/docs/api/java/util/Optional.html)

It is an easy way to make sure that everyone has to check if they have (not) received a `null`.


## Basic usage

```php
use PetrKnap\Optional\Optional;

/** @var Optional<string> $stringOption */
$stringOption = Optional::of('data');
if ($stringOption->isPresent()) {
    echo $stringOption->get();
}

Optional::ofFalsable(tmpfile())->ifPresent(function ($tmpFile): void {
    fwrite($tmpFile, 'data');
    fclose($tmpFile);
}, else: fn () => print('tmpfile() failed'));
```


## Creating your own typed optional

The library provides specialized traits to help you implement strictly typed wrappers with full static analysis support.

### Quick Overview

1. **[`NonGenericOptional` trait](./src/NonGenericOptional.php)** for fixed specific types like `bool` or `string` (see [`OptionalString` class](./tests/Some/OptionalString.php))
   ```php
   use PetrKnap\Optional\Optional;
   use PetrKnap\Optional\NonGenericOptional;

   /** @extends Optional<bool> */
   final class OptionalBool extends Optional
   {
       use NonGenericOptional;

       protected static function isSupported(mixed $value): bool
       {
           return is_bool($value);
       }
   }
   ```
2. **[`GenericOptional` trait](./src/GenericOptional.php)** for generic structures like `array` (see [`OptionalArray` class](./tests/Some/OptionalArray.php))
3. **[`AbstractOptional` trait](./src/AbstractOptional.php)** for extendable factory optionals (use this when you need a base class for other typed optionals, see [`OptionalObject` class](./tests/Some/OptionalObject.php))

### Type registration

You can register your custom typed optionals into [`TypedOptional` helper](./src/TypedOptional.php).
Once registered, calling the base [`Optional` class](./src/Optional.php) will automatically look up and return the correct specific subclass if the value matches its criteria.
```php
use PetrKnap\Optional\TypedOptional;
use PetrKnap\Optional\Optional;

TypedOptional::register(OptionalBool::class);

printf(
    "Class name of optional which holds `true` is `%s`.\n",
    get_class(Optional::of(true)),
);
```
```
Class name of optional which holds `true` is `OptionalBool`.
```

---

# ⚠ SILENT BREAKING CHANGE ⚠

## `Optional::equals()` Behavior Alignment

The `equals()` method has been refactored to align strictly with `Optional` specification.

1. **Comparing two empty `Optional`s now evaluates to `true`.**
   Comparing any two empty `Optional` instances evaluates to `true`, regardless of their underlying type.
   ```java
   Optional<String> emptyOptStr = Optional.empty();
   Optional<Array> emptyOptArr = Optional.empty();
   emptyOptStr.equals(emptyOptArr); // true
   ```
2. **Comparing an `Optional` against a raw value now evaluates to `false`.**
   Passing non-optional values directly into `equals()` will immediately evaluate to `false`.
   ```java
   Optional.of("").equals(""); // false
   ```

---

Run `composer require petrknap/optional` to install it.
You can [support this project via donation](https://petrknap.github.io/donate.html).
The project is licensed under [the terms of the `LGPL-3.0-or-later`](./COPYING.LESSER).
