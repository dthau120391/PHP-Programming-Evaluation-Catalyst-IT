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
require_once "ValidationHelper.php";
require_once "User.php";

class UserUploader
{
    private static function getDirectives($args)
    {
        //Skip file name
        array_shift($args);
        $args = join($args, ' ');

        //Get all directives
        preg_match_all('/ (--\w+ ([= ][^-][^ ]+)?) | (-\w+ ([= ][^-][^ ]+)? )/x', $args, $match);
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
            if ((!ValidationHelper::isValidCommand($command) && $isCommand) || (!ValidationHelper::isValidFlag($command) && $isFlag)) {
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

    private static function createTable($config)
    {
        return User::buildUserTable($config);
    }

    private static function parseCSV($filePath)
    {
        if (is_file($filePath)) {
            $csvProcessor = new CSVProcessor($filePath);
            //Check valid header
            if(ValidationHelper::isValidCSVHeaders($csvProcessor->getHeaders()))
            {
                $data = $csvProcessor->parse();
                return $data;
            }
            return null;
        }
    }

    private static function userUpload($userData, $config)
    {
        if(!empty($userData))
        {
            foreach($userData as $data)
            {
                if(!ValidationHelper::isExistedEmail($data["email"], $config))
                {
                    $user = new User($data["name"], $data["surname"], $data["email"]);
                    $user->save($config);
                }
                else
                {
                    echo "Data: " . $data["name"] . ", " . $data["surname"] . ", " . $data["email"] . "\n";
                    echo "Existed Email: " . $data["email"] . "\n\n";
                }
            }
        }
    }

    private static function dryRun($userData)
    {
        if(!empty($userData))
        {
            $users = [];

            foreach($userData as $data)
            {
                  $users[] = new User($data["name"], $data["surname"], $data["email"]);
            }

            return $users;
        }
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
            //Get config database detail
            $config["hostname"] = array_key_exists('h', $flags) ? $flags["h"] : "";
            $config["username"] = array_key_exists('u', $flags) ? $flags["u"] : "";
            $config["password"] = array_key_exists('p', $flags) ? $flags["p"] : "";

            //Create user table
            if (array_key_exists('create_table', $commands)) {
                if(self::createTable($config))
                {
                    echo "Create Database Table successfully!";
                }
                return;
            }

            //Process cvs file
            if (array_key_exists('file', $commands)) {
                $userData = self::parseCSV($commands["file"]);
                //Insert to database if it is not dry_run mode
                if(!array_key_exists('dry_run', $commands))
                {
                    if(self::createTable($config))
                    {
                        $config["dbname"]   = "catalyst_test";
                        self::userUpload($userData, $config);
                    }
                }else{
                    self::dryRun($userData);
                }
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
