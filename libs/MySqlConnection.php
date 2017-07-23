<?php
namespace App;
/**
 * Process a CSV file as an input and parse file data is to be
 * inserted into a MySQL database
 *
 * @author     dthau120391@gmail.com
 * @version    1.0
 */
use PDO;

class MySqlConnection
{
    // PDO Object
    private $pdo;

    //Create a new connection instance.
    public function __construct(array $config)
    {
        $this->open($config);
    }

    //Destroy an exists connection instance.
    public function __destruct()
    {
        $this->close();
    }

    //Open connection
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

    //Execute a sql query.
    public function execute($sql)
    {
        return $this->pdo->exec($sql);
    }

    //Send a sql query to get results.
    public function query($sql, array $params = [])
    {
        if (!empty($params)) {
            $result = $this->pdo->prepare($sql);
            $result->execute($params);
            return $result;
        }

        return $this->pdo->query($sql);
    }

    //Close connection.
    private function close()
    {
        $this->pdo = null;
    }

    //Get the connection string with format 'mysql:dbname=...;host=...;'
    private function getConnectionString(array $config)
    {
        return 'mysql:host=' . $config['hostname'] . (array_key_exists("dbname", $config) ? ';dbname=' . $config['dbname'] : "");
    }
}
?>
