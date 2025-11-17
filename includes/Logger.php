<?php
/**
 * Logger - Simple logging utility for errors and debug information
 *
 * Features:
 * - Multiple log levels (ERROR, WARNING, INFO, DEBUG)
 * - File-based logging
 * - Log rotation
 * - Configurable log directory
 */

class Logger {

    const ERROR = 'ERROR';
    const WARNING = 'WARNING';
    const INFO = 'INFO';
    const DEBUG = 'DEBUG';

    private $logDir;
    private $logFile;
    private $enabled;
    private $minLevel;

    private static $levels = [
        'ERROR' => 1,
        'WARNING' => 2,
        'INFO' => 3,
        'DEBUG' => 4
    ];

    /**
     * Constructor
     *
     * @param string|null $logDir Log directory path
     * @param bool $enabled Enable/disable logging
     * @param string $minLevel Minimum log level to record
     */
    public function __construct($logDir = null, $enabled = true, $minLevel = 'INFO') {
        $this->logDir = $logDir ?? __DIR__ . '/../logs';
        $this->enabled = $enabled;
        $this->minLevel = $minLevel;
        $this->logFile = $this->logDir . '/app_' . date('Y-m-d') . '.log';

        // Create log directory if it doesn't exist
        if ($this->enabled && !file_exists($this->logDir)) {
            @mkdir($this->logDir, 0755, true);
        }
    }

    /**
     * Check if level should be logged
     *
     * @param string $level Log level
     * @return bool
     */
    private function shouldLog($level) {
        if (!$this->enabled) {
            return false;
        }

        $currentLevel = self::$levels[$level] ?? 0;
        $minLevel = self::$levels[$this->minLevel] ?? 0;

        return $currentLevel <= $minLevel;
    }

    /**
     * Write log entry
     *
     * @param string $level Log level
     * @param string $message Log message
     * @param array $context Additional context data
     * @return bool Success status
     */
    private function write($level, $message, $context = []) {
        if (!$this->shouldLog($level)) {
            return false;
        }

        $timestamp = date('Y-m-d H:i:s');
        $contextString = !empty($context) ? ' | ' . json_encode($context) : '';
        $logEntry = "[{$timestamp}] [{$level}] {$message}{$contextString}" . PHP_EOL;

        $result = @file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);

        // Rotate log if it gets too large (> 10MB)
        if (file_exists($this->logFile) && filesize($this->logFile) > 10 * 1024 * 1024) {
            $this->rotateLogs();
        }

        return $result !== false;
    }

    /**
     * Log error message
     *
     * @param string $message Error message
     * @param array $context Additional context
     * @return bool
     */
    public function error($message, $context = []) {
        return $this->write(self::ERROR, $message, $context);
    }

    /**
     * Log warning message
     *
     * @param string $message Warning message
     * @param array $context Additional context
     * @return bool
     */
    public function warning($message, $context = []) {
        return $this->write(self::WARNING, $message, $context);
    }

    /**
     * Log info message
     *
     * @param string $message Info message
     * @param array $context Additional context
     * @return bool
     */
    public function info($message, $context = []) {
        return $this->write(self::INFO, $message, $context);
    }

    /**
     * Log debug message
     *
     * @param string $message Debug message
     * @param array $context Additional context
     * @return bool
     */
    public function debug($message, $context = []) {
        return $this->write(self::DEBUG, $message, $context);
    }

    /**
     * Log API request
     *
     * @param string $endpoint API endpoint
     * @param string $method HTTP method
     * @param int $httpCode Response HTTP code
     * @param float $duration Request duration in seconds
     */
    public function logApiRequest($endpoint, $method, $httpCode, $duration) {
        $this->info("API Request", [
            'endpoint' => $endpoint,
            'method' => $method,
            'http_code' => $httpCode,
            'duration_ms' => round($duration * 1000, 2)
        ]);
    }

    /**
     * Rotate log files
     *
     * @return bool
     */
    private function rotateLogs() {
        if (!file_exists($this->logFile)) {
            return false;
        }

        $rotatedFile = $this->logFile . '.' . time();
        return @rename($this->logFile, $rotatedFile);
    }

    /**
     * Clean old log files (older than specified days)
     *
     * @param int $days Number of days to keep
     * @return int Number of files deleted
     */
    public function cleanOldLogs($days = 30) {
        $deleted = 0;
        $files = glob($this->logDir . '/*.log*');

        if ($files === false) {
            return 0;
        }

        $cutoffTime = time() - ($days * 24 * 60 * 60);

        foreach ($files as $file) {
            if (filemtime($file) < $cutoffTime) {
                if (@unlink($file)) {
                    $deleted++;
                }
            }
        }

        return $deleted;
    }

    /**
     * Get log statistics
     *
     * @return array Log stats
     */
    public function getStats() {
        $files = glob($this->logDir . '/*.log*');
        $totalSize = 0;

        if ($files !== false) {
            foreach ($files as $file) {
                $totalSize += filesize($file);
            }
        }

        return [
            'enabled' => $this->enabled,
            'total_files' => count($files),
            'total_size_bytes' => $totalSize,
            'total_size_mb' => round($totalSize / 1024 / 1024, 2),
            'current_log_file' => $this->logFile,
            'log_dir' => $this->logDir
        ];
    }
}
