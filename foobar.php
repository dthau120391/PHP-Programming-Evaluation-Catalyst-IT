<?php
/**
 * Output the numbers from 1 to 100
 * Where the number is divisible by three (3) output the word “foo”
 * Where the number is divisible by five (5) output the word “bar”
 * Where the number is divisible by three (3) and (5) output the word “foobar”
 *
 * @author     dthau120391@gmail.com
 * @version    1.0
 */
for($i = 1; $i <= 100; $i++)
{
	if($i % 15 ==0)
	{
		echo "foobar, ";
		continue;
	}

	if($i % 3 == 0)
	{
		echo "foo, ";
		continue;
	}
	
	if($i % 5 == 0)
	{
		echo "bar, ";
		continue;
	}

	echo $i . ", ";
	
}

echo "\n";
?>
