<?php

namespace Otus\Logger;
use \Bitrix\Main\Diag\FileExceptionHandlerLog;
class OtusExceptionHandler extends FileExceptionHandlerLog
{
    public function write($exception, $logType)
    {
        $logMessage = "OTUS - Exception: " . $exception->getMessage() . PHP_EOL;
        $logMessage .= "OTUS - File: " . $exception->getFile() . " Line: " . $exception->getLine() . PHP_EOL;
        $logMessage .= "OTUS - Trace: " . PHP_EOL;

        foreach ($exception->getTrace() as $index => $trace) {
            $logMessage .= sprintf(
                "OTUS -  #%d %s(%s): %s%s%s" . PHP_EOL,
                $index,
                $trace['file'] ?? "",
                $trace['line'] ?? "",
                $trace['class'] ?? "",
                $trace['type'] ?? "",
                $trace['function'] ?? "",
            );
        }

        file_put_contents(
            $_SERVER["DOCUMENT_ROOT"] . "/otus/log/debug.txt",
            $logMessage,
            FILE_APPEND
        );
    }

}