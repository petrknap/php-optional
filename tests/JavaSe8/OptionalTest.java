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
        // two empties
        assert (Optional.<Integer>empty().equals(Optional.<Alternative>empty()) == true);
        // wrong type
        assert (Optional.of(1).equals(Optional.of("1")) == false);
    }

    private static void testFilter() {
        Optional $thisOption;
        // present option returns itself on true
        $thisOption = Optional.of(1); assert ($thisOption.filter($v -> true) == $thisOption);
        // present option returns empty option on false
        assert (Optional.of(1).filter($v -> false).equals(Optional.empty()));
        // empty option returns itself
        $thisOption = Optional.empty(); assert ($thisOption.filter($v -> true) == $thisOption);
        $thisOption = Optional.empty(); assert ($thisOption.filter($v -> false) == $thisOption);
        // empty option does not call predicate
        Optional.empty().filter($v -> { assert (false); return true; });
    }

    private static void testFlatMap() {
        Optional $alternativeOption, $thisOption;
        // present option returns mapped option
        $alternativeOption = Optional.<Alternative>empty(); assert (Optional.of(1).flatMap($v -> $alternativeOption) == $alternativeOption);
        // empty option returns itself
        $thisOption = Optional.empty(); assert ($thisOption.flatMap($v -> Optional.of(new Alternative())) == $thisOption);
        // empty option does not call mapper
        Optional.empty().flatMap($v -> { assert (false); return Optional.<Alternative>empty(); });
    }

    private static void testGetters() {
        // present option returns value
        assert (Optional.of(1).get() == 1);
        assert (Optional.of(1).orElse(2) == 1);
        assert (Optional.of(1).orElseGet(() -> 2) == 1);
        assert (Optional.of(1).orElseThrow(() -> new AlternativeException()) == 1);
        // empty option returns fallback
        assert (Optional.<Integer>empty().orElse(1) == 1);
        assert (Optional.<Integer>empty().orElseGet(() -> 1) == 1);
        // empty option throws
        try { Optional.empty().get(); } catch (NoSuchElementException $e) { assert (true); }
        try { Optional.empty().orElseThrow(() -> new AlternativeException()); } catch (AlternativeException $e) { assert (true); }
        // fallback can be null
        assert (Optional.empty().orElse(null) == null);
        assert (Optional.empty().orElseGet(() -> null) == null);
    }

    private static void testIfPresent() {
        // present option calls consumer with value
        Optional.of(1).ifPresent($v -> { assert ($v == 1); });
        // empty option does not call consumer
        Optional.empty().ifPresent($v -> { assert (false); });
    }

    private static void testIsPresent() {
        assert (Optional.of(1).isPresent() == true);
        assert (Optional.empty().isPresent() == false);
    }

    private static void testMap() {
        Optional $thisOption;
        // present option returns mapped option
        assert (Optional.of(1).map($v -> "2").equals(Optional.of("2")));
        // present option returns empty option for null
        assert (Optional.of(1).map($v -> null).equals(Optional.empty()));
        // empty option returns itself
        $thisOption = Optional.empty(); assert ($thisOption.map($v -> "2") == $thisOption);
        // empty option does not call mapper
        Optional.empty().map($v -> { assert (false); return null; });
    }

    private static class Alternative {
    }

    private static class AlternativeException extends RuntimeException {
    }
}
