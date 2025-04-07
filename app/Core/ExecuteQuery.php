<?php

namespace App\Core;

require_once __DIR__ . '/../../config/config.php';

use PDO;
use PDOException;
use Exception;
use InvalidArgumentException;
use App\Utils\Logger;

class ExecuteQuery extends Database {
    private Database $database;
    private PDO $pdo;
    private Logger $logger;

    public function __construct() {
        $this->database = Database::getInstance();
        $this->pdo = $this->database->getConnection();
        $this->logger = new Logger(LOGS_PATH);
    }

    public function endConnection(): void {
        $this->database->disconnect();
    }

    // Método para executar SELECT
    public function select(string $table, array $columns = ['*'], string $where = '', array $params = []): array {
        try {
            $columnsStr = implode(", ", $columns);
            $sql = "SELECT $columnsStr FROM $table";

            if ($where) {
                $sql .= " WHERE $where";
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log do erro
            $this->logger->error("Erro ao executar SELECT na tabela $table: " . $e->getMessage());
            throw new \Exception("Erro ao executar SELECT: " . $e->getMessage());
        }
    }

    // Método para executar INSERT
    public function insert(string $table, array $data): bool {
        try {
            // Validação para evitar injeção manual do nome da tabela
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
                throw new InvalidArgumentException("Nome de tabela inválido.");
            }

            if (empty($data)) {
                throw new InvalidArgumentException("Os dados para inserção não podem estar vazios.");
            }

            $columns = array_keys($data);
            $values = array_values($data);

            $columnsStr = implode(", ", $columns);
            $placeholders = implode(", ", array_fill(0, count($data), '?'));

            $sql = "INSERT INTO $table ($columnsStr) VALUES ($placeholders)";

            $stmt = $this->pdo->prepare($sql);
            $success = $stmt->execute($values);

            if ($success) {
                $lastId = $this->getLastInsertedId();
                $this->logger->info("Novo registro inserido na tabela $table. ID: $lastId.");
            }

            return $success;
        } catch (InvalidArgumentException $e) {
            // Loga e lança apenas argumentos inválidos
            $this->logger->error("Erro de validação ao inserir na tabela $table: " . $e->getMessage());
            throw $e;
        }catch (PDOException $e) {
            // Log do erro
            $this->logger->error("Erro ao executar INSERT na tabela $table: " . $e->getMessage());
            throw new Exception("Erro ao executar INSERT: " . $e->getMessage());
        }
    }

    // Método para executar UPDATE
    public function update(string $table, array $data, string $where, array $params): bool {
        try {
            $setPart = [];
            foreach ($data as $column => $value) {
                $setPart[] = "$column = ?";
            }
            $setStr = implode(", ", $setPart);

            $sql = "UPDATE $table SET $setStr WHERE $where";
            
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute(array_merge(array_values($data), $params));
        } catch (PDOException $e) {
            // Log do erro
            $this->logger->error("Erro ao executar UPDATE na tabela $table: " . $e->getMessage());
            throw new Exception("Erro ao executar UPDATE: " . $e->getMessage());
        } 
    }

    // Método para executar DELETE
    public function delete(string $table, string $where, array $params): bool {
        try {
            $sql = "DELETE FROM $table WHERE $where";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            // Log do erro
            $this->logger->error("Erro ao executar DELETE na tabela $table: " . $e->getMessage());
            throw new Exception("Erro ao executar DELETE: " . $e->getMessage());
        }
    }

    // Método para obter o último ID inserido
    public function getLastInsertedId(): ?int {
        try {
            $lastId = $this->pdo->lastInsertId();
            if ($lastId) {
                return (int) $lastId;
            }
            return null;
        } catch (PDOException $e) {
            $this->logger->error("Erro ao obter lastInsertId: " . $e->getMessage());
            throw new Exception("Erro ao obter lastInsertId: " . $e->getMessage());
        }
    }
}


?>