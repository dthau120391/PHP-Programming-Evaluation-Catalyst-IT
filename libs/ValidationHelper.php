<?php
namespace App;

/**
 * Validate data
 *
 * Class ValidationHelper
 * @package App
 */
require_once "MySqlConnection.php";

class ValidationHelper
{
    const EMAIL_VALIDATION = "/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/";
    const NAME_VALIDATION = "/^[a-zA-Z'. -]+$/";

    /**
     * Validate email
     *
     * @param $email
     * @return bool
     */
    public static function validateEmail($email) {
        if (preg_match(self::EMAIL_VALIDATION, $email)) {
            return true;
        }
        return false;
    }

    /**
     * Validate name
     *
     * @param $name
     * @return bool
     */
    public static function validateName($name) {
        if (preg_match(self::NAME_VALIDATION, $name)) {
            return true;
        }
        return false;
    }

    /**
     * Validate existed email
     *
     * @param $email
     * @param $config
     * @return bool
     */
    public static function isExistedEmail($email, $config) {
        if(!empty($email) && self::validateEmail($email))
        {
            $mysqlConnection = new MySqlConnection($config);

            if(!empty($mysqlConnection))
            {
                //Insert into database
                $query  = "SELECT * FROM users WHERE users.email = '$email'";
                $result = $mysqlConnection->query($query);
                if($result !== false && $result->rowCount())
                {
                    return true;
                }
            }
        }


        return false;
    }

    /**
     * Validate csv headers
     *
     * @param $headers
     * @return bool
     */
    public static function isValidCSVHeaders($headers)
    {
        $validCSVHeaders = ["name", "surname", "email"];

        return $headers == $validCSVHeaders;
    }

    /**
     * Validate command
     *
     * @param $command
     * @return bool
     */
    public static function isValidCommand($command)
    {
        $validCommands = ["file", "create_table", "dry_run", "help"];

        return in_array($command, $validCommands);
    }

    /**
     * Validate flag
     *
     * @param $flag
     * @return bool
     */
    public static function isValidFlag($flag)
    {
        $validFlags = ["u", "p", "h"];

        return in_array($flag, $validFlags);
    }
}

?>
