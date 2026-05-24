<?php
/**
 * Database.php - Database Connection Class (Singleton Pattern)
 * Uses PDO for database operations
 */

class Database
{
    private static $instance = null;
    private $connection;
    
    private $host = DB_HOST;
    private $dbname = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $charset = 'utf8mb4';

    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}"
        ];

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            $this->applyRuntimeMigrations();
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    /**
     * Apply lightweight, idempotent schema updates needed by new features.
     */
    private function applyRuntimeMigrations()
    {
        // Add per-submission performance score to avoid reusing old master score.
        if (!$this->columnExists('pengajuan', 'nilai_kinerja_pengajuan')) {
            $sql = "ALTER TABLE pengajuan
                    ADD COLUMN nilai_kinerja_pengajuan DECIMAL(5,2) NULL
                    AFTER alasan_pengajuan";
            $this->connection->exec($sql);
        }
    }

    /**
     * Check if a column exists in a table.
     */
    private function columnExists($table, $column)
    {
        // MySQL does not support parameter binding for identifiers, so escape manually
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $column = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
        $sql = "SHOW COLUMNS FROM `{$table}` LIKE '{$column}'";
        $stmt = $this->connection->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    /**
     * Get singleton instance
     * @return Database
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get PDO connection
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * Prevent unserialization
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}
