<?php

declare(strict_types=1);

namespace PetrKnap\Optional;

use Psr\Log\LoggerInterface;

/**
 * @internal
 */
trait Logger
{
    private static ?LoggerInterface $logger = null;

    public static function setLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    /**
     * @todo add file and line into context
     */
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
