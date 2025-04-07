<?php 

namespace App\Utils;

require_once __DIR__ . '/../../config/config.php';

class Logger {
    protected string $logFilePath;
    protected string $logLevel;
    protected string $logMessage;
    protected \DateTime $logDateTime;

    const LOG_LEVEL_INFO = 'INFO';
    const LOG_LEVEL_ERROR = 'ERROR';
    const LOG_LEVEL_WARNING = 'WARNING';
    const LOG_LEVEL_DEBUG = 'DEBUG';
    const LOG_LEVEL_NOTICE = 'NOTICE';

    private array $validLogLevels = [
        self::LOG_LEVEL_INFO,
        self::LOG_LEVEL_ERROR,
        self::LOG_LEVEL_WARNING,
        self::LOG_LEVEL_DEBUG,
        self::LOG_LEVEL_NOTICE
    ];

    public function __construct(string $logFilePath, string $logLevel = self::LOG_LEVEL_INFO) {
        $this->logFilePath = $logFilePath;
        $this->setLogLevel($logLevel);
        $this->logDateTime = new \DateTime();
    }

    protected function setLogLevel(string $logLevel): void {
        if (!in_array($logLevel, $this->validLogLevels)) {
            throw new \InvalidArgumentException("Nível de log inválido: {$logLevel}. Os níveis válidos são: " . implode(", ", $this->validLogLevels));
        }
        $this->logLevel = $logLevel;
    }

    protected function setLogMessage(string $message): void {
        $this->logMessage = $message;
        $formattedMessage = $this->formatLogMessage();
        $this->writeToLog($formattedMessage);
    }

    protected function formatLogMessage(): string {
        return $this->logDateTime->format('Y-m-d H:i:s') . " [{$this->logLevel}] - {$this->logMessage}\n";
    }

    protected function writeToLog(string $message): void {
        file_put_contents($this->logFilePath, $message, FILE_APPEND);
    }

    // Métodos específicos para níveis de log
    public function info(string $message): void {
        $this->setLogLevel(self::LOG_LEVEL_INFO);
        $this->setLogMessage($message);
    }

    public function error(string $message): void {
        $this->setLogLevel(self::LOG_LEVEL_ERROR);
        $this->setLogMessage($message);
    }

    public function warning(string $message): void {
        $this->setLogLevel(self::LOG_LEVEL_WARNING);
        $this->setLogMessage($message);
    }

    public function debug(string $message): void {
        $this->setLogLevel(self::LOG_LEVEL_DEBUG);
        $this->setLogMessage($message);
    }

    public function notice(string $message): void {
        $this->setLogLevel(self::LOG_LEVEL_NOTICE);
        $this->setLogMessage($message);
    }
}

?>