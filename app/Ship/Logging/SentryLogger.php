<?php

namespace App\Ship\Logging;


use InvalidArgumentException;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RavenHandler;
use Monolog\Logger;
use Raven_Client;

class SentryLogger
{
    /**
     * The Log levels.
     *
     * @var array
     */
    protected $levels = [
        'debug' => Logger::DEBUG,
        'info' => Logger::INFO,
        'notice' => Logger::WARNING,
        'warning' => Logger::WARNING,
        'error' => Logger::ERROR,
        'critical' => Logger::CRITICAL,
        'alert' => Logger::ALERT,
        'emergency' => Logger::EMERGENCY,
    ];

    public function __invoke(array $config)
    {
        $client = new Raven_Client($config['project_url']);
        $handler = new RavenHandler($client, $this->level($config));
        $handler->setFormatter(
            new LineFormatter("%message% %context% %extra%\n")
        );
        $logger = new Logger('sentry', [$handler]);
        return $logger;
    }

    /**
     * Parse the string level into a Maven_Client constant.
     *
     * @param  array $config
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    protected function level(array $config)
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }

        throw new InvalidArgumentException('Invalid log level.');
    }
}