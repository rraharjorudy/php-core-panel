<?php

if (!function_exists('ensure_log_dir')) {
    function ensure_log_dir(string $logDir): void
    {
        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }
    }
}

if (!function_exists('delete_old_logs')) {
    function delete_old_logs(string $logDir, string $prefix, int $maxFiles = 7): void
    {
        $files = glob($logDir . DIRECTORY_SEPARATOR . $prefix . '-*.log');

        // Sort files by date in filename descending (newest first)
        usort($files, function ($a, $b) {
            return filemtime($b) <=> filemtime($a);
        });

        // Delete files exceeding maxFiles
        $filesToDelete = array_slice($files, $maxFiles);
        foreach ($filesToDelete as $file) {
            @unlink($file);
        }
    }
}

if (!function_exists('log_message')) {
    function log_message(string $level, string $message): void
    {
        $logDir = base_path('storage/logs');
        ensure_log_dir($logDir);

        // Delete old logs for this level (info or error)
        delete_old_logs($logDir, $level, 7);

        $date = date('Y-m-d');
        $logFile = $logDir . DIRECTORY_SEPARATOR . "{$level}-{$date}.log";

        $formattedMessage = sprintf("[%s] %s: %s%s", date('Y-m-d H:i:s'), strtoupper($level), $message, PHP_EOL);

        file_put_contents($logFile, $formattedMessage, FILE_APPEND);
    }
}

if (!function_exists('log_error')) {
    function log_error(string $message): void
    {
        log_message('error', $message);
    }
}

if (!function_exists('log_info')) {
    function log_info(string $message): void
    {
        log_message('info', $message);
    }
}
