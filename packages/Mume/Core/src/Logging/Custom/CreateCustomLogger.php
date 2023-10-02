<?php

namespace Mume\Core\Logging\Custom;

use Monolog\Logger;

class CreateCustomLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     *
     * @return Logger
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('Log');
        $logger->pushHandler(new CustomLoggerHandler($config));
        return $logger;
    }
}
