# Optional (as in Java Platform SE 8 but in PHP)

> A container object which may or may not contain a non-null value. If a value is present, `isPresent()` will return `true` and `get()` will return the value.
>
> --
> [Optional (Java Platform SE 8)](https://docs.oracle.com/javase/8/docs/api/java/util/Optional.html)

It is an easy way to make sure that everyone has to check if they have (not) received a `null`.

```php
namespace PetrKnap\Optional;

/** @var Optinal<string> $optionalString */
$optionalString = new Optional('value');

echo $optionalString->isPresent() ? $optionalString->get() : 'EMPTY';
```

---

Run `composer require petrknap/optional` to install it.
You can [support this project via donation](https://petrknap.github.io/donate.html).
The project is licensed under [the terms of the `LGPL-3.0-or-later`](./COPYING.LESSER).
