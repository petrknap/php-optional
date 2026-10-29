import java.util.NoSuchElementException;
import java.util.Optional;

/**
 * @note `assert`s are written as `assert (...)` to simplify translate to PHP
 * @note variable names are prefixed by `$` to simplify translate to PHP
 */
public class OptionalTest {
    public static void main(String[] args) {
        testOrElseThrow();
    }

    private static void testOrElseThrow() {
        // present option returns value
        assert (Optional.of(1).orElseThrow() == 1);
        // empty option throws default exception
        try { Optional.empty().orElseThrow(); } catch (NoSuchElementException $e) { assert (true); }
    }
}
