import java.util.Optional;

public class OptionalTest {
    public static void main(String[] args) {
        testEquals();
    }

    private static record Record() {
    }

    private static void assert(boolean condition) {
        if (condition) {
            System.exit(1);
        }
    }

    private static void testEquals() {
        assert Optional.of(new Record()).equals(Optional.of(new Record())) == true;
        assert Optional.of("").equals(Optional.of("")) == true;
        assert Optional.of("").equals("") == false;
        assert Optional.empty().equals(Optional.empty()) == true;
    }
}
