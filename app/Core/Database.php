<?php 

namespace App\Core;

require_once __DIR__ . '/../../config/config.php';

use PDO;
use PDOException;
use App\Utils\Logger;
use App\Utils\EnvLoader;
use Exception;

class Database {
    private string $host;
    private string $dbName;
    private string $username;
    private string $password;
    private string $charset;
    private ?PDO $pdo = null;
    private Logger $logger;

    private function __construct() {
        // Carrega as variáveis de ambiente de forma controlada
        $envVariables = EnvLoader::loadEnvVariables(ENV_FILE_PATH);

        // Inicializa as propriedades do objeto com as variáveis carregadas
        $this->host = EnvLoader::getFromArray('DB_HOST', $envVariables);
        $this->dbName = EnvLoader::getFromArray('DB_NAME', $envVariables);
        $this->username = EnvLoader::getFromArray('DB_USERNAME', $envVariables);
        $this->password = EnvLoader::getFromArray('DB_PASSWORD', $envVariables);
        $this->charset = EnvLoader::getFromArray('DB_CHARSET', $envVariables);
        
        // Inicializa o logger com o caminho configurado
        $this->logger = new Logger(LOGS_PATH);
    }

    // Método para criar a conexão
    private function connect(): void {
        if ($this->pdo === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
                $this->logger->info('Conexão efetuada com sucesso.');
            } catch (PDOException $err) {
                $this->logger->error("Erro ao conectar ao banco de dados: " . $err->getMessage());
                throw new PDOException('Erro ao conectar: ' . $err->getMessage());
            }
        }
    }

    // Método Singleton
    protected static function getInstance(): self {
        static $instance = null;
        if ($instance === null) {
            $instance = new Database();
        }
        return $instance;
    }

    // Método para obter a conexão PDO
    protected function getConnection(): PDO {
        $this->connect();
        return $this->pdo;
    }

    // Método para encerrar a conexão
    protected function disconnect(): void {
        $this->pdo = null; // Fecha a conexão
        $this->logger->info('Conexão com o banco de dados encerrada.');
    }

    // Método para impedir clonagem
    private function __clone() {}

    // Método para impedir desserialização
    public function __wakeup() {
        throw new Exception("Desserialização não permitida para esta classe.");
    }
}

?>