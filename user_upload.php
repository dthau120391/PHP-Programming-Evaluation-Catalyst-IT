<?php
/**
 * Process a CSV file as an input and parse file data is to be
 * inserted into a MySQL database
 *
 * @author     dthau120391@gmail.com
 * @version    1.0
 */


function getDirectives($args)
{
	//Skip file name
	array_shift($args);
	$args = join($args, ' ');
    	
	//Get all directives
    	preg_match_all('/ (--\w+ (?:[= ] [^- ]+)?) | (-\w+ (?:[= ] [^- ]+)? )/x', $args, $match );	
	$args = array_shift($match);

    	$directives = array(
        	'commands' => array(),
        	'flags'    => array()
    	);

    	foreach ($args as $arg)
	{
		//Checking Flag or Command
		$isCommand = substr($arg, 0, 2) === '--';
		$isFlag = substr($arg, 0, 1) === '-';
		
		//Get directive and value
		$value = preg_split('/[= ]/', $arg, 2);
		$com   = substr(array_shift($value), $isCommand ? 2 : 1);
		$value = join($value);

        	//Is it a command? (prefixed with "--")
        	if ($isCommand) 
		{
		    	$directives['commands'][$com] = !empty($value) ? $value : true;
		    	continue;
        	}

        	//Is it a flag? (prefixed with "-")
        	if ($isFlag) 
		{	
		    	$directives['flags'][$com] = !empty($value) ? $value : true;
		    	continue;
        	}
        	continue;
    	}
    	return $directives;
}

function processDirectives($directives)
{
	$commands = $directives["commands"];
	$flags = $directives["flags"];

	if(!empty($commands))
	{

		if(array_key_exists('create_table', $commands))
		{

		}

		if(array_key_exists('file', $commands))
		{

		}	

	}
	else
	{
		echo "Try --help to output the list of directives with details\n";
	}
}

function printHelp()
{
	echo "\n***************Help User Upload************\n\n";
	echo "--file [csv file name] – this is the name of the CSV to be parsed\n\n";
	echo "--create_table – this will cause the MySQL users table to be built (and no further action will be taken)\n\n";
	echo "--dry_run – this will be used with the --file directive in the instance that we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered.\n\n";
	echo "-u – MySQL username\n\n";
	echo "-p – MySQL password\n\n";
	echo "-h – MySQL host\n\n";
}

function userUpload()
{
	
}

function main($args)
{
	//Get directives
	$directives = getDirectives($args);
	//Process directives
	processDirectives($directives);
}

main($argv);
?>
