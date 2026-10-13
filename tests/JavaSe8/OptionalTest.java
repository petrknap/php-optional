import java.util.NoSuchElementException;
import java.util.Optional;

/**
 * @note `assert`s are written as `assert (...)` to simplify translate to PHP
 * @note variable names are prefixed by `$` to simplify translate to PHP
 */
public class OptionalTest {
    public static void main(String[] args) {
        testEquals();
        testFilter();
        testFlatMap();
        testGetters();
        testIfPresent();
        testIsPresent();
        testMap();
    }

    private static void testEquals() {
        assert (Optional.of(1).equals(Optional.of(1)) == true);
        assert (Optional.of(1).equals(1) == false);
        assert (Optional.of(1).equals(Optional.of(2)) == false);
        assert (Optional.of(1).equals(Optional.empty()) == false);
        // two records
        assert (Optional.of(new Record()).equals(Optional.of(new Record())) == true);
        // two empties
        assert (Optional.<Integer>empty().equals(Optional.<Another>empty()) == true);
        // wrong type
        assert (Optional.of(1).equals(Optional.of("1")) == false);
    }

    private static void testFilter() {
        assert (Optional.of(1).filter($v -> true).isPresent() == true);
        assert (Optional.of(1).filter($v -> false).isPresent() == false);
        // empty
        assert (Optional.empty().filter($v -> true).isPresent() == false);
    }

    private static void testFlatMap() {
        /**
         * @todo
         * Optional $o1;
         * $o1 = Optional.<Another>empty(); assert (Optional.of(1).flatMap($v -> $o1) == $o1);
         */
        Optional $o2;
        $o2 = Optional.<Another>empty(); assert (Optional.empty().flatMap($v -> $o2) != $o2);
    }

    private static void testGetters() {
        assert (Optional.of(1).get() == 1);
        assert (Optional.<Integer>empty().orElse(1) == 1);
        assert (Optional.<Integer>empty().orElseGet(() -> 1) == 1);
        assert (Optional.of(1).orElseThrow() == 1);
        try { Optional.empty().orElseThrow(); } catch (NoSuchElementException $e) { assert (true); }
        // to nullable
        assert (Optional.of(1).orElse(null) == 1);
        assert (Optional.empty().orElse(null) == null);
    }

    private static void testIfPresent() {
        Optional.of(1).ifPresent($v -> { assert (true); });
        Optional.empty().ifPresent($v -> { assert (false); });
    }

    private static void testIsPresent() {
        assert (Optional.of(1).isPresent() == true);
        assert (Optional.empty().isPresent() == false);
    }

    private static void testMap() {
        assert (Optional.of(1).map($v -> "2").orElseThrow() == "2");
        assert (Optional.empty().map($v -> "2").isPresent() == false);
    }

    private static record Record() {
    }

    private static class Another {
    }
}
