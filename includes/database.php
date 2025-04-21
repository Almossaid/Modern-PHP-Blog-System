<?php
require_once 'config.php';
require_once 'pdo_database.php';

// Initialize global PDO connection
$pdo = PDODatabase::getInstance()->getPDO();

class Database {
    private $connection;
    private static $instance = null;
    private $inTransaction = false;

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
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $this->connection->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
            $this->connection->set_charset('utf8mb4');
            $this->connection->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw $e;
        }
    }

    public function query(string $sql, array $params = []) {
        if (!$this->connection || !$this->connection->ping()) {
            $this->connect();
        }

        try {
            $stmt = $this->connection->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing query: " . $this->connection->error);
            }

            if (!empty($params)) {
                $types = $this->getParamTypes($params);
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            
            if ($this->isWriteQuery($sql)) {
                return true;
            }
            
            $result = $stmt->get_result();
            if ($result === false) {
                throw new Exception("Query execution error: " . $stmt->error);
            }

            return $result;
        } catch (Exception $e) {
            if ($this->inTransaction) {
                $this->rollback();
            }
            error_log("Query error: " . $e->getMessage());
            throw $e;
        } finally {
            if (isset($stmt) && $stmt instanceof mysqli_stmt) {
                $stmt->close();
            }
        }
    }

    public function fetchAll(string $sql, array $params = []): array {
        $result = $this->query($sql, $params);
        return $result instanceof mysqli_result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function fetch(string $sql, array $params = []): ?array {
        $result = $this->query($sql, $params);
        return $result instanceof mysqli_result ? $result->fetch_assoc() : null;
    }

    public function execute(string $sql, array $params = []): bool {
        return $this->query($sql, $params) === true;
    }

    public function escape(string $string): string {
        return $this->connection->real_escape_string($string);
    }

    public function lastInsertId(): int {
        return $this->connection->insert_id;
    }

    public function beginTransaction(): void {
        if (!$this->inTransaction) {
            $this->connection->begin_transaction();
            $this->inTransaction = true;
        }
    }

    public function commit(): void {
        if ($this->inTransaction) {
            $this->connection->commit();
            $this->inTransaction = false;
        }
    }

    public function rollback(): void {
        if ($this->inTransaction) {
            $this->connection->rollback();
            $this->inTransaction = false;
        }
    }

    private function getParamTypes(array $params): string {
        $types = '';
        foreach ($params as $param) {
            if ($param === null) {
                $types .= 's';
                continue;
            }
            switch (gettype($param)) {
                case 'integer':
                    $types .= 'i';
                    break;
                case 'double':
                    $types .= 'd';
                    break;
                case 'string':
                    $types .= 's';
                    break;
                case 'boolean':
                    $types .= 'i';
                    break;
                default:
                    $types .= 'b';
            }
        }
        return $types;
    }

    private function isWriteQuery(string $sql): bool {
        $command = strtoupper(strtok(trim($sql), " "));
        return in_array($command, ['INSERT', 'UPDATE', 'DELETE', 'REPLACE', 'ALTER', 'DROP', 'CREATE']);
    }

    public function __destruct() {
        if ($this->inTransaction) {
            $this->rollback();
        }
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
?>