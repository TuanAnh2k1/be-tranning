<?php

use Mume\Core\Logging\Custom\CreateCustomLogger;
use Mume\Core\Logging\DatabaseLogs\CreateDatabaseLogger;

return [
    'channels' => [
        'my_custom' => [
            'driver' => 'custom',
            'via' => CreateCustomLogger::class,
        ],
        'sql_query' => [
            'driver' => 'custom',
            'via' => CreateDatabaseLogger::class,
        ],
    ],
];
