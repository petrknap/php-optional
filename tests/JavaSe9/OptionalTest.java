import java.util.Optional;

/**
 * @note `assert`s are written as `assert (...)` to simplify translate to PHP
 * @note variable names are prefixed by `$` to simplify translate to PHP
 */
public class OptionalTest {
    public static void main(String[] args) {
        testIfPresentOrElse();
        testOr();
    }

    private static void testIfPresentOrElse() {
        // present option calls action with value
        Optional.of(1).ifPresentOrElse($v -> { assert ($v == 1); }, () -> { });
        // empty option calls empty action
        Optional.empty().ifPresentOrElse($v -> { }, () -> { assert (true); });
        // present option does not call empty action
        Optional.of(1).ifPresentOrElse($v -> { }, () -> { assert (false); });
        // empty option does not call action
        Optional.empty().ifPresentOrElse($v -> { assert (false); }, () -> { });
    }

    private static void testOr() {
        Optional $suppliedOption, $thisOption;
        // present option returns itself
        $thisOption = Optional.of(1); assert ($thisOption.or(() -> Optional.empty()) == $thisOption);
        // empty option returns supplied option
        $suppliedOption = Optional.empty(); assert (Optional.empty().or(() -> $suppliedOption) == $suppliedOption);
        // present option does not call supplier
        Optional.of(1).or(() -> { assert (false); return Optional.empty(); });
    }
}
