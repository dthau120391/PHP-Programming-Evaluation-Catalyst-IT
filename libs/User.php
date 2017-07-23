<?php
namespace App;

require_once "MySqlConnection.php";
require_once "ValidationHelper.php";

/**
 *
 *
 * Class User
 * @package    App
 * @author     dthau120391@gmail.com
 * @version    1.0
 */
class User
{
    private $name;
    private $surname;
    private $email;
    private $data;

    public function __construct($name, $surname, $email)
    {
        $this->setData(trim($name), trim($surname), trim($email));
        $this->setName(trim($name));
        $this->setSurname(trim($surname));
        $this->setEmail(trim($email));
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        if (!empty($name) && ValidationHelper::validateName($name)) {
            $this->name = ucfirst(strtolower($name));
        }else{
            $this->printData();
            echo "Invalid name: " . $name . "\n\n";
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($name, $surname, $email)
    {
        $this->data["name"] = $name;
        $this->data["surname"] = $surname;
        $this->data["email"] = $email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $surname
     */
    public function setSurname($surname)
    {
        if (!empty($surname) && ValidationHelper::validateName($surname)) {
            $this->surname = ucfirst(strtolower($surname));
        }else{
            $this->printData();
            echo "Invalid surname: " . $surname . "\n\n";
        }
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        if (!empty($email) && ValidationHelper::validateEmail($email)) {
            $this->email = strtolower($email);
        }else{
            $this->printData();
            echo "Invalid Email: " . $email . "\n\n";
        }
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function toString(){
        return $this->getName() . ", " . $this->getSurname() . ", " . $this->getEmail() . "\n";
    }

    public function printData(){
        echo "Data: " . $this->data["name"] . ", " . $this->data["surname"] . ", " . $this->data["email"] . "\n";
    }

    public function save($config)
    {
        $mysqlConnection = new MySqlConnection($config);

        if(!empty($mysqlConnection) && !empty($this->getName()) && !empty($this->getSurname()) && !empty($this->getEmail()))
        {
            //Insert into database
            $query = "INSERT INTO users(name,surname, email) VALUES(\"{$this->getName()}\", \"{$this->getSurname()}\", \"{$this->getEmail()}\")";

            if(!$mysqlConnection->execute($query))
            {
                echo "Fail to insert user: " . $this->toString() . "\n";
            }
        }
    }

    public static function buildUserTable($config)
    {
        $mysqlConnection = new MySqlConnection($config);

        if(!empty($mysqlConnection))
        {
            try {
                //Create database if not existed
                $query = "CREATE DATABASE IF NOT EXISTS catalyst_test";
                $mysqlConnection->execute($query);
                //Select database
                $query = "use catalyst_test";
                $mysqlConnection->execute($query);
                //Create users table
                $query = "
                CREATE TABLE IF NOT EXISTS users (
                    `id` int(10) NOT NULL,
                    `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `surname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                    `deleted_at` timestamp NULL DEFAULT NULL,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                //Add Constraint
                $mysqlConnection->execute($query);
                $query = "
                ALTER TABLE `users`
                      ADD PRIMARY KEY (`id`),
                      ADD UNIQUE KEY `email` (`email`);
                      MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;";
                $mysqlConnection->execute($query);

                return true;
            }
            catch(\PDOException $e)
            {
                echo "PDOException: " . $e->getMessage();
                return false;
            }
        }
    }
}

?>
