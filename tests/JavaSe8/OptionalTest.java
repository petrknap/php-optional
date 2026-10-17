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
        Optional $thisOption;
        // present option returns itself on true
        $thisOption = Optional.of(1); assert ($thisOption.filter($v -> true) == $thisOption);
        // present option returns empty option on false
        assert (Optional.of(1).filter($v -> false).isPresent() == false);
        // empty option does not call predicate
        Optional.empty().filter($v -> { assert (false); return true; }).isPresent();
    }

    private static void testFlatMap() {
        Optional $anotherOption, $thisOption;
        // present option returns mapped option
        $anotherOption = Optional.<Another>empty(); assert (Optional.of(1).flatMap($v -> $anotherOption) == $anotherOption);
        // empty option returns itself
        $thisOption = Optional.empty(); assert ($thisOption.flatMap($v -> Optional.of(new Another())) == $thisOption);
        // empty option skips mapper
        Optional.empty().flatMap($v -> { assert (false); return Optional.<Another>empty(); });
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
        Optional $thisOption;
        // present option returns mapped option
        assert (Optional.of(1).map($v -> "2").orElseThrow() == "2");
        // present option returns empty option for null
        assert (Optional.of(1).map($v -> null).isPresent() == false);
        // empty option returns itself
        $thisOption = Optional.empty(); assert ($thisOption.map($v -> "2") == $thisOption);
        // empty option skips mapper
        Optional.empty().map($v -> { assert (false); return null; });
    }

    private static record Record() {
    }

    private static class Another {
    }
}
