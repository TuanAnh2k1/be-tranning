<?php

namespace Mume\Core\Logging\DatabaseLogs;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class DatabaseLoggerHandler extends AbstractProcessingHandler
{
    public function __construct($config = null)
    {
        parent::__construct($config['level'] ?? Logger::DEBUG);
    }

    /**
     * @param  array  $record
     */
    public function write(array $record): void
    {
        try {
            $logFolderPath = storage_path('logs');
            $fileName      = now()->toDateString();
            $logDisk       = $logFolderPath.'/DatabaseQuery';
            if (!is_dir($logDisk)) {
                mkdir($logDisk, 0777, true);
            }
            $extension = '.txt';
            $filePath  = $logDisk.'/'.$fileName.$extension;
            $content   = now()->toDateTimeString().' '.config('app.timezone').' - IP:'.Request::ip().' - Level '.$record['level_name'].':'.$record['message']."\n";

            if (file_exists($filePath)) {
                file_put_contents($filePath, $content, FILE_APPEND | LOCK_EX);
            } else {
                $fp = fopen($filePath, "wb");
                fwrite($fp, $content);
                fclose($fp);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
