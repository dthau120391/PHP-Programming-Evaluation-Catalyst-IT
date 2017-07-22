<?php
namespace App;

/**
 *
 *
 * Class ValidationHelper
 * @package App
 */
require_once "MySqlConnection.php";

class ValidationHelper
{
    const EMAIL_VALIDATION = "/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/";
    const NAME_VALIDATION = "/^[a-zA-Z'. -]+$/";

    public static function validateEmail($email) {
        if (preg_match(self::EMAIL_VALIDATION, $email)) {
            return true;
        }
        return false;
    }

    public static function validateName($name) {
        if (preg_match(self::NAME_VALIDATION, $name)) {
            return true;
        }
        return false;
    }

    public static function isExistedEmail($email) {
        return false;
    }

    public static function isValidCommand($command)
    {
        $validCommands = ["file", "create_table", "dry_run", "help"];

        return in_array($command, $validCommands);
    }

    public static function isValidFlag($flag)
    {
        $validFlags = ["u", "p", "h"];

        return in_array($flag, $validFlags);
    }
}

?>
