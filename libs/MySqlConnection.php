<?php
namespace App;
/**
 * Connect Database and process query
 *
 * @author     dthau120391@gmail.com
 * @version    1.0
 */
use PDO;

class MySqlConnection
{
    // PDO Object
    private $pdo;

    /**
     * MySqlConnection constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->open($config);
    }

    /**
     * Destroy existed mysql connection
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Open connection
     *
     * @param array $config
     */
    private function open(array $config)
    {
        try {
            $this->pdo = new PDO(
                $this->getConnectionString($config),
                $config['username'],
                $config['password']
            );
        } catch (\PDOException $e) {
            echo "PDO Exception: " . $e->getMessage() . "\n";
            die();
        }

    }

    /**
     * Execute query
     *
     * @param $sql
     * @return mixed
     */
    public function execute($sql)
    {
        return $this->pdo->exec($sql);
    }

    /**
     * Execute query and get result
     *
     * @param $sql
     * @param array $params
     * @return mixed
     */
    public function query($sql, array $params = [])
    {
        if (!empty($params)) {
            $result = $this->pdo->prepare($sql);
            $result->execute($params);
            return $result;
        }

        return $this->pdo->query($sql);
    }

    /**
     * Close connection
     */
    private function close()
    {
        $this->pdo = null;
    }

    /**
     * Get connection string
     *
     * @param array $config
     * @return string
     */
    private function getConnectionString(array $config)
    {
        return 'mysql:host=' . $config['hostname'] . (array_key_exists("dbname", $config) ? ';dbname=' . $config['dbname'] : "");
    }
}
?>
