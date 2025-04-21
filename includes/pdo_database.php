<?php
require_once 'config.php';

class PDODatabase {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $this->connect();
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect(): void {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw $e;
        }
    }

    public function getPDO(): PDO {
        if (!$this->pdo) {
            $this->connect();
        }
        return $this->pdo;
    }

    public function query(string $sql, array $params = []) {
        try {
            $stmt = $this->getPDO()->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query error: " . $e->getMessage());
            throw $e;
        }
    }

    public function fetchAll(string $sql, array $params = []): array {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetch(string $sql, array $params = []): ?array {
        $result = $this->query($sql, $params)->fetch();
        return $result !== false ? $result : null;
    }

    public function execute(string $sql, array $params = []): bool {
        return $this->query($sql, $params)->rowCount() > 0;
    }

    public function lastInsertId(): string {
        return $this->getPDO()->lastInsertId();
    }
}