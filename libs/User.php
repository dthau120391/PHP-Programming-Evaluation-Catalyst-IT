<?php
namespace App;

require_once "MySqlConnection.php";

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

    /**
     * User constructor.
     * @param $name
     * @param $surname
     * @param $email
     */
    public function __construct($name, $surname, $email)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
        $this->surname = $surname;
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
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }


    public function save()
    {

    }

}

?>
