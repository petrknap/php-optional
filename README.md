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

$stringOption = Optional::of('data');
if ($stringOption->isPresent()) {
    echo $stringOption->get();
}

Optional::ofFalsable(tmpfile())->ifPresent(function ($tmpFile): void {
    fwrite($tmpFile, 'data');
    fclose($tmpFile);
}, else: fn () => print('tmpfile() failed'));
```


## Creating your own optional

The library provides specialized traits to help you implement your own wrappers with full static analysis support:

- **[`GenericOptional` trait](./src/GenericOptional.php)** for generic structures like `object` and `array` (see [`OptionalArray` class](./tests/Some/OptionalArray.php))
   ```php
   use PetrKnap\Optional\Optional;
   use PetrKnap\Optional\GenericOptional;

   /**
    * @template-covariant T of Model
    *
    * @extends Optional<T>
    */
   final class OptionalModel extends Optional
   {
       /** @use GenericOptional<T> */
       use GenericOptional;

       protected static function isSupported(mixed $value): bool
       {
           return $value instanceof Model;
       }
   }

   abstract class Model {} // your base model
   ```
- **[`NonGenericOptional` trait](./src/NonGenericOptional.php)** for fixed specific types like `bool` (see [`OptionalBool` class](./tests/Some/OptionalBool.php))

---

# ⚠ BREAKING CHANGE ⚠

## `Optional::equals()` Behavior Alignment

The `equals()` method has been refactored to align strictly with `Optional` specification.

1. 
    **Comparing two empty `Optional`s now evaluates to `true`.**
    Previously, comparing two empty `Optional`s checks their types.
    ```java
    Optional<String> emptyOptStr = Optional.empty();
    Optional<Array> emptyOptArr = Optional.empty();
    System.out.println(emptyOptStr.equals(emptyOptArr)); // true
    ```
2.
    **Comparing an `Optional` with a raw value now evaluates to `false`.**
    Previously, an `Optional` could be compared directly with a raw value.
    ```java
    System.out.println(Optional.of("").equals("")); // false
    ```
3.
    **Comparing an `Optional<object>` will be by strict default.**
    Previously, an `Optional<object>` compares loosely by default.
    ```java
    Optional.of(new X()).equals(Optional.of(new X())); // false
    ```

---

Run `composer require petrknap/optional` to install it.
You can [support this project via donation](https://petrknap.github.io/donate.html).
The project is licensed under [the terms of the `LGPL-3.0-or-later`](./COPYING.LESSER).
