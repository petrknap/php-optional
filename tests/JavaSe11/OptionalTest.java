import java.util.Optional;

/**
 * @note `assert`s are written as `assert (...)` to simplify translate to PHP
 * @note variable names are prefixed by `$` to simplify translate to PHP
 */
public class OptionalTest {
    public static void main(String[] args) {
        testIsEmpty();
    }

    private static void testIsEmpty() {
        assert (Optional.of(1).isEmpty() == false);
        assert (Optional.empty().isEmpty() == true);
    }
}
