import java.util.Optional;

public class OptionalTest {
    public static void main(String[] args) {
        testEquals();
    }

    private static void testEquals() {
        assert Optional.of(new Record()).equals(Optional.of(new Record())) == true;
        assert Optional.of(new Record()).equals(new Record()) == false;
        assert Optional.empty().equals(Optional.empty()) == true;
    }

    private static record Record() {
    }
}
