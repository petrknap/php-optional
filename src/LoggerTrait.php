<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use Psr\Log\LoggerInterface;

/**
 * @internal
 */
trait LoggerTrait
{
    private static ?LoggerInterface $logger = null;

    public static function setLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    protected static function logNotice(string $message): void
    {
        if (self::$logger === null) {
            trigger_error(
                $message,
                error_level: E_USER_NOTICE,
            );
        } else {
            self::$logger->notice($message);
        }
    }
}
