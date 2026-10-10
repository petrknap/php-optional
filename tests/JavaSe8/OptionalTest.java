import java.util.Optional;

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
        assert Optional.of(1).equals(Optional.of(1)) == true;
        assert Optional.of(1).equals(1) == false;
        assert Optional.of(1).equals(Optional.of(2)) == false;
        assert Optional.of(1).equals(Optional.empty()) == false;
        // two records
        assert Optional.of(new Record()).equals(Optional.of(new Record())) == true;
        // two empties
        assert Optional.empty().equals(Optional.empty()) == true;
        // wrong type
        assert Optional.of(1).equals(Optional.of("1")) == false;
    }

    private static void testFilter() {
        assert Optional.of(1).filter(value -> true).isPresent() == true;
        assert Optional.of(1).filter(value -> false).isPresent() == false;
        // empty
        assert Optional.empty().filter(value -> true).isPresent() == false;
    }

    private static void testFlatMap() {
        assert Optional.of(1).flatMap(value -> Optional.of("2")).orElseThrow() == "2";
        assert Optional.empty().flatMap(value -> Optional.of("2")).isPresent() == false;
    }

    private static void testGetters() {
        assert Optional.of(1).get() == 1;
        assert Optional.empty().orElse(1) == 1;
        assert Optional.empty().orElseGet(() -> 1) == 1;
        assert Optional.of(1).orElseThrow() == 1;
        // to nullable
        assert Optional.of(1).orElse(null) == 1;
        assert Optional.empty().orElse(null) == null;
    }

    private static void testIfPresent() {
        Optional.of(1).ifPresent(value -> { assert true; });
        Optional.empty().ifPresent(value -> { assert false; });
    }

    private static void testIsPresent() {
        assert Optional.of(1).isPresent() == true;
        assert Optional.empty().isPresent() == false;
    }

    private static void testMap() {
        assert Optional.of(1).map(value -> "2").orElseThrow() == "2";
        assert Optional.empty().map(value -> "2").isPresent() == false;
    }

    private static record Record() {
    }
}
