<?php

namespace Mume\Core\Logging\Custom;

use Exception;
use Monolog\Logger;
use Mume\Core\Helpers\CommonHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Monolog\Handler\AbstractProcessingHandler;

class CustomLoggerHandler extends AbstractProcessingHandler
{
    public function __construct($config = null)
    {
        parent::__construct($config['level'] ?? Logger::DEBUG);
    }

    public function write(array $record): void
    {
        try {
            $logFolderPath = storage_path('logs');
            $moduleInfo = CommonHelper::getModuleInfo();
            if ($moduleInfo->module == '') {
                $moduleInfo->module = 'Common';
            }

            $folderName = now()->toDateString();
            $logDisk = $logFolderPath . '/' . $folderName;
            if (!is_dir($logDisk)) {
                mkdir($logDisk, 0777, true);
            }
            $fileName = $moduleInfo->module . '-' . $folderName;
            $extension = '.log';
            $filePath = $logDisk . '/' . $fileName . $extension;
            $content = now()->toDateTimeString() . ' ' . config('app.timezone') . ' - Ip:' . Request::ip() . ' - Level ' . $record['level_name'] . ':' . $record['message'] . "\n";
            if (file_exists($filePath)) {
                file_put_contents($filePath, $content, FILE_APPEND | LOCK_EX);
            } else {
                $fp = fopen($filePath, "wb");
                if ($fp == true) {
                    fwrite($fp, $content);
                    fclose($fp);
                }
            }
        } catch (Exception $e) {
            $message = $e->getMessage() . " in line " . $e->getLine() . "-" . $e->getFile() . "\n" . $e->getTraceAsString();
            Log::error($message);
        }
    }
}
