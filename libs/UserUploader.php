<?php
namespace App;

/**
 * Process a CSV file as an input and parse file data is to be
 * inserted into a MySQL database
 *
 * @author     dthau120391@gmail.com
 * @version    1.0
 */
require_once "MySqlConnection.php";
require_once "CSVProcessor.php";

class UserUploader
{
    private static function isValidCommand($command)
    {
        $validCommands = ["file", "create_table", "dry_run", "help"];

        return in_array($command, $validCommands);
    }

    private static function isValidFlag($flag)
    {
        $validFlags = ["u", "p", "h"];

        return in_array($flag, $validFlags);
    }

    private static function getDirectives($args)
    {
        //Skip file name
        array_shift($args);
        $args = join($args, ' ');

        //Get all directives
        preg_match_all('/ (--\w+ (?:[= ] [^ ]+)?) | (-\w+ (?:[= ] [^ ]+)? )/x', $args, $match);
        $args = array_shift($match);

        $directives = array(
            'commands' => array(),
            'flags' => array()
        );

        foreach ($args as $arg) {
            //Checking Flag or Command
            $isCommand = false;
            $isFlag = false;

            if (substr($arg, 0, 2) === '--') {
                $isCommand = true;
            } elseif (substr($arg, 0, 1) === '-') {
                $isFlag = true;
            }

            //Get directive and value
            $value = preg_split('/[= ]/', $arg, 2);

            //Get and validate directive
            $command = substr(array_shift($value), $isCommand ? 2 : 1);
            if ((!self::isValidCommand($command) && $isCommand) || (!self::isValidFlag($command) && $isFlag)) {
                echo "\nUnknown directive: " . $command;
                self::printHelp();
                exit(0);
            }

            //Get value
            $value = join($value);

            //Is it a command? (prefixed with "--")
            if ($isCommand) {
                $directives['commands'][$command] = !empty($value) ? $value : true;
                continue;
            }

            //Is it a flag? (prefixed with "-")
            if ($isFlag) {
                $directives['flags'][$command] = !empty($value) ? $value : true;
                continue;
            }
            continue;
        }
        return $directives;
    }

    private static function createTable()
    {

    }

    private static function parseCSV($filePath)
    {
        if (is_file($filePath)) {
            $csvProcessor = new CSVProcessor($filePath);
            $data = $csvProcessor->parse();
            return $data;
        }
    }

    private static function userUpload($users)
    {

    }

    private static function printHelp()
    {
        echo "\n***************Help User Upload************\n\n";
        echo "--file [csv file name] – this is the name of the CSV to be parsed\n\n";
        echo "--create_table – this will cause the MySQL users table to be built (and no further action will be taken)\n\n";
        echo "--dry_run – this will be used with the --file directive in the instance that we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered.\n\n";
        echo "-u – MySQL username\n\n";
        echo "-p – MySQL password\n\n";
        echo "-h – MySQL host\n\n";
    }


    private static function processDirectives($directives)
    {
        $commands = $directives["commands"];
        $flags = $directives["flags"];

        if (!empty($commands)) {
            //Create user table
            if (array_key_exists('create_table', $commands)) {
                self::createTable();
                return;
            }

            //Process cvs file
            if (array_key_exists('file', $commands)) {
                $users = self::parseCSV($commands["file"]);
                self::userUpload($users);
            }

            //Print list of directive with details
            if (array_key_exists('help', $commands)) {
                self::printHelp();
            }

        } else {
            echo "Try --help to output the list of directives with details\n";
        }
    }


    public static function run($args)
    {
        //Get directives
        $directives = self::getDirectives($args);
        //Process directives
        self::processDirectives($directives);
    }
}
?>
