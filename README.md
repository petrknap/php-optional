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

## Examples

```php
namespace PetrKnap\Optional;

/** @var Optional<string> $optionalString */
$optionalString = Optional::of('data');
if ($optionalString->isPresent()) {
    echo $optionalString->get();
}

OptionalResource::ofFalsable(tmpfile())->ifPresent(function ($tmpFile): void {
    fwrite($tmpFile, 'data');
    fclose($tmpFile);
}, else: fn () => print('tmpfile() failed'));
```

### Create and use your own typed optional

```php
namespace PetrKnap\Optional;

/**
 * @extends OptionalObject<Some\DataObject>
 */
class YourOptional extends OptionalObject {
    protected static function getInstanceOf(): string {
        return Some\DataObject::class;
    }
}
TypedOptional::register(YourOptional::class); // optional recommended step

function your_strong_typed_function(YourOptional $input): YourOptional {
    return YourOptional::empty();
}

/**
 * @param Optional<Some\DataObject> $input
 *
 * @return Optional<Some\DataObject>
 */
function your_weak_typed_function(Optional $input): Optional {
    return YourOptional::empty();
}
```

---

Run `composer require petrknap/optional` to install it.
You can [support this project via donation](https://petrknap.github.io/donate.html).
The project is licensed under [the terms of the `LGPL-3.0-or-later`](./COPYING.LESSER).
