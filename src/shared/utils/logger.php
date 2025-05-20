<?php

// function ensure_log_file(string $filePath): void
// {
//     $logDir = dirname($filePath);

//     // Create the directory if it doesn't exist
//     if (!is_dir($logDir)) {
//         mkdir($logDir, 0775, true);
//     }

//     // Create the log file if it doesn't exist
//     if (!file_exists($filePath)) {
//         touch($filePath);
//         chmod($filePath, 0664);
//     }
// }

function ensure_log_dir(string $logDir): void
{
    if (!is_dir($logDir)) {
        mkdir($logDir, 0775, true);
    }
}

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

function log_error(string $message): void
{
    log_message('error', $message);
}

function log_info(string $message): void
{
    log_message('info', $message);
}


// function log_error(string $message): void
// {
//     $logFile = base_path('storage/logs/error.log');
//     ensure_log_file($logFile);

//     file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] ERROR: ' . $message . PHP_EOL, FILE_APPEND);
// }

// function log_info(string $message): void
// {
//     $logFile = base_path('storage/logs/info.log');
//     ensure_log_file($logFile);
//     file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] INFO: ' . $message . PHP_EOL, FILE_APPEND);
// }
