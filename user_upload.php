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
	$args = join( $args, ' ' );
    	
	//
    	preg_match_all('/ (--\w+ (?:[= ] [^-]+ [^\s-] )? ) | (-\w+) | (\w+) /x', $args, $match );
	var_dump($match);die;
    	$args = array_shift( $match );

    	$directives = array(
        	'input'    => array(),
        	'commands' => array(),
        	'flags'    => array()
    	);

    	foreach ($args as $arg)
	{
        	//Is it a command? (prefixed with "--")
        	if (substr($arg, 0, 2) === '--') 
		{
		    	$value = preg_split('/[= ]/', $arg, 2 );
		    	$com   = substr(array_shift($value), 2 );
		    	$value = join($value);

		    	$ret['commands'][$com] = !empty($value) ? $value : true;
		    	continue;
        	}

        	//Is it a flag? (prefixed with "-")
        	if (substr($arg, 0, 1) === '-') 
		{
		    	$ret['flags'][] = substr($arg, 1);
		    	continue;
        	}

        	$ret['input'][] = $arg;
        	continue;
    	}
    	return $directives;
}
?>
