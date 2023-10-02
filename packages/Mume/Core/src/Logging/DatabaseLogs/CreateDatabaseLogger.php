<?php

namespace Mume\Core\Logging\DatabaseLogs;

use Monolog\Logger;

class CreateDatabaseLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return Logger
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('log');
        $logger->pushHandler(new DatabaseLoggerHandler($config));

        return $logger;
    }


}
