<?php

namespace App\Ship\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\UidProcessor;

class JoJoTooLog
{
    /**
     * @param Logger $logger
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $formatter = new LineFormatter(
                "%datetime%\t%extra.uid%\t%channel%\t%level_name%\t%message%\t%context%\t%extra%\n",
                null,
                null,
                true
            );
            $formatter->includeStacktraces();
            $handler->pushProcessor(new UidProcessor());
            $handler->setFormatter($formatter);
        }
    }

    /**
     * We update or set the log path according to process user
     * so that we can know if log is printed by php-fpm or dev
     */
    public static function configPathByProcessUser()
    {
        $processUser = posix_getpwuid(posix_geteuid());
        $logPath = storage_path("logs/laravel-{$processUser['name']}.log");
        config(['logging.channels.daily.path' => $logPath]);
    }
}
