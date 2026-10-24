# Optional (like in Java Platform SE 8 but in PHP)

It is an easy way to make sure that everyone has to check if they have (not) received a value.

> A container object which may or may not contain a non-null value. If a value is present, `isPresent()` will return `true` and `get()` will return the value.
>
> Additional methods that depend on the presence or absence of a contained value are provided, such as `orElse()` (return a default value if value not present) and `ifPresent()` (execute a block of code if the value is present).
>
> This is a [value-based](https://docs.oracle.com/javase/8/docs/api/java/lang/doc-files/ValueBased.html) class; use of identity-sensitive operations (including reference equality (==), identity hash code, or synchronization) on instances of Optional may have unpredictable results and should be avoided.
>
> --
> [Optional (Java Platform SE 8)](https://docs.oracle.com/javase/8/docs/api/java/util/Optional.html)

This library delivers complete and reliable behavior of the `Optional`.
The entire feature set is [explicitly described and checked by an `OptionalTest.java`](./tests/JavaSe8/OptionalTest.java).
This ensures **absolute reliability and alignment with the Java specification**.


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

This library provides [`OptionalOverrides` trait](src/OptionalOverrides.php) to help you implement your own wrapper with full static analysis support (see [`Some\Optional` class](./tests/Some/Optional.php)):

```php
use PetrKnap\Optional\Optional;
use PetrKnap\Optional\OptionalOverrides;

/**
 * @template-covariant T of mixed type of non-null value
 *
 * @extends Optional<T>
 */
final class MyOptional extends Optional
{
   /** @use OptionalOverrides<T> */
   use OptionalOverrides;
}
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
