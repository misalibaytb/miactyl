<?php
//db static classes.
namespace App\Base;


class DB
{
    private static $instance;
    private $manager;
}

/**
 * An abstract class representing a database instance.
 * It only supports SQL dialects probably?
 */
abstract class DBInstance
{
    protected $schema;

    protected $credentials;

    protected $unsafe;

    /**
     * Constructor for the DBInstance class.
     *
     * @param string $schema The SQL query used to create tables.
     * @param string $prefix An optional prefix for the table names.
     * @return bool Returns true if the instance is successfully created, false otherwise.
     */
    abstract public function __construct(string $schema, array $credentials, bool $unsafe = false);

    /**
     * Creates the necessary tables in the database.
     *
     * @return bool Returns true if the tables are successfully created, false otherwise.
     */
    abstract protected function tables(): bool;

    /**
     * Connects to the database using the provided credentials.
     *
     * @param array $credentials An associative array containing the database connection credentials.
     * @return bool Returns true if the connection is successfully established, false otherwise.
     */
    abstract protected function connect(array $credentials): bool;

    /**
     * Terminates the database connection.
     *
     * @return bool Returns true if the connection is successfully terminated, false otherwise.
     */
    abstract protected function kill(): bool;

    /**
     * Destructor for the DBInstance class.
     *
     */
    abstract public function __destruct();

    /**
     * Executes a query on the database.
     *
     * @param string $query The SQL query to be executed.
     * @return mixed Returns the result of the query execution.
     */
    public function query(string $query): mixed
    {
        if (!$this->tables()) {
            if ($this->unsafe) {
                trigger_error("Failed to create tables, using unsafe query. To disable this, create the instance with \$unsafe set to true.", E_WARNING);
            }
        }
        return $this->unsafeQuery($query);
    }

    /**
     * Executes a query on the database without any safety checks.
     *
     * @param string $query The SQL query to be executed.
     * @return mixed Returns the result of the unsafe query execution.
     */
    abstract protected function unsafeQuery(string $query): mixed;
}


class MysqliInstance extends DBInstance
{
    protected $connection;

    // Constructor for the MysqliInstance class.

    /**
     * @throws Exception Throws an exception if the MySQLi extension is not loaded.
     */
    public function __construct(string $schema, array $credentials, bool $unsafe = false)
    {
        $this->unsafe = $unsafe;
        if (!extension_loaded('mysqli')) {
            throw new Exception('The MySQLi extension is not loaded.');
        }
        $this->schema = $schema;
        $this->connect($credentials);
        $this->tables();
    }

    /**
     * Destructor for the MysqliInstance class.
     * Terminates the database connection.
     */
    public function __destruct()
    {
        $this->kill();
    }

    /**
     * Executes a query on the database without any safety checks.
     *
     *
     * @param string $query The SQL query to be executed.
     * @return void
     */
    public function unsafeQuery(string $query): mixed
    {
        try {
            return $this->connection->query($query);
        } catch (mysqli_sql_exception $e) {
            throw new Exception("MySQLi Error: " . $e->getMessage());
        }
    }

    /**
     * Terminates the database connection.
     *
     * @return bool Returns true if the connection is successfully terminated, false otherwise.
     */
    public function kill(): bool
    {
        if (empty($this->connection)) {
            return false;
        }
        $this->connection->close();
        return true;
    }

    /**
     * Creates the necessary tables in the database.
     *
     * @return bool Returns true if the tables are successfully created, false otherwise.
     */
    public function tables(): bool
    {
        return $this->connection->multi_query($this->schema);
    }

    /**
     * Connects to the database using the provided credentials.
     *
     * @param array $credentials An associative array containing the database connection credentials.
     *  Format: ['host' => 'host:port', 'username' => '', 'password' => '', 'database' => '']
     * @return bool Returns true if the connection is successfully established, false otherwise.
     */
    protected function connect(array $credentials): bool
    {
        if (!$this->connection = new mysqli($credentials['host'], $credentials['username'], $credentials['password'], $credentials['database'])) {
            return false;
        } else {
            return true;
        }
    }
}

class PDOInstance extends DBInstance
{
    private $connection;

    /**
     * Constructor for the PDOInstance class.
     *
     * @param string $schema The SQL query used to create tables.
     * @param array $credentials An associative array containing the database connection credentials.
     * @param bool $unsafe An optional flag to disable safety checks.
     * @throws Exception Throws an exception if the MySQLi extension is not loaded.
     */
    public function __construct(string $schema, array $credentials, bool $unsafe = false)
    {
        $this->unsafe = $unsafe;
        if (!extension_loaded('mysqli')) {
            throw new Exception('The MySQLi extension is not loaded.');
        }
        $this->schema = $schema;
        $this->connect($credentials);
        $this->tables();
    }

    /**
     * Destructor for the PDOInstance class.
     * Terminates the database connection.
     */
    public function __destruct()
    {
        $this->kill();
    }

    /**
     * Executes a query on the database without any safety checks.
     *
     * @param string $query The SQL query to be executed.
     * @return mixed Returns the result of the query execution or false if the query fails.
     */
    public function unsafeQuery(string $query): mixed
    {
        try {
            $stmt = $this->connection->query($query);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Query failed: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Terminates the database connection.
     *
     * @return bool Returns true if the connection is successfully terminated, false otherwise.
     */
    public function kill(): bool
    {
        if (empty($this->connection)) {
            return false;
        }
        $this->connection = null;
        return true;
    }

    /**
     * Creates the necessary tables in the database.
     *
     * @return bool Returns true if the tables are successfully created, false otherwise.
     */
    public function tables(): bool
    {
        return $this->connection->exec($this->schema);
    }

    /**
     * Connects to the database using the provided credentials.
     *
     * @param array $credentials An associative array containing the database connection credentials.
     *  Format: ['host' => 'host:port', 'username' => '', 'password' => '', 'database' => '']
     * @return bool Returns true if the connection is successfully established, false otherwise.
     */
    protected function connect(array $credentials): bool
    {
        if (!$this->connection = new \PDO("mysql:host={$credentials['host']};dbname={$credentials['database']}", $credentials['username'], $credentials['password'])) {
            return false;
        } else {
            return true;
        }
    }
}
