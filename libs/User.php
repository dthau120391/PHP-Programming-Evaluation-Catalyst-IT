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

    public function __construct($name, $surname, $email)
    {
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
            echo "Invalid name: " . $name . "\n";
        }
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
            echo "Invalid surname: " . $surname . "\n";
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
        if (!empty($email) && ValidationHelper::validateEmail($email) && !ValidationHelper::isExistedEmail($email)) {
            $this->email = strtolower($email);
        }else{
            echo "Invalid Email or Exited Email: " . $email . "\n";
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

    public function save($dbname, $username, $password)
    {
        $config["dbname"] = $dbname;
        $config["username"] = $username;
        $config["password"] = $password;

        $mysqlConnection = new MySqlConnection($config);

        if(!empty($mysqlConnection) && !empty($this->getName()) && !empty($this->getSurname()) && !empty($this->getEmail()))
        {
            //Insert into database
            $query = "INSERT INTO users(name,surname, email, created_at) VALUES()";
            if(!$mysqlConnection->execute($query))
            {
                echo "Fail to insert user: " . $this->toString();
            }
        }
    }

    public static function buildUserTable()
    {

    }
}

?>
