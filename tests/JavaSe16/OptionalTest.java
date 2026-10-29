import java.util.Optional;

/**
 * @note `assert`s are written as `assert (...)` to simplify translate to PHP
 * @note variable names are prefixed by `$` to simplify translate to PHP
 */
public class OptionalTest {
    public static void main(String[] args) {
        testEquals();
    }

    private static void testEquals() {
        Record $record;
        // same record instance
        $record = new Record(1); assert (Optional.of($record).equals(Optional.of($record)) == true);
        // two equal record instances
        assert (Optional.of(new Record(1)).equals(Optional.of(new Record(1))) == true);
        // two different record instances
        assert (Optional.of(new Record(1)).equals(Optional.of(new Record(2))) == false);
    }

    private static record Record(int value) {
    }
}
