<?php
namespace App;

/**
 * Process a CSV file as an input and parse file data is to be
 * inserted into a MySQL database
 *
 * @author     dthau120391@gmail.com
 * @version    1.0
 */

class CSVProcessor
{
	private $file; 
    	private $headers; 
	private $data;
    	private $delimiter; 
    	private $length;

	public function __construct($filePath, $delimiter=",", $length=1000) 
	    { 
		$this->file = fopen($filePath, "r");
		$this->delimiter = $delimiter; 
		$this->length = $length;
		
		if($this->file)
		{
			$this->headers = fgetcsv($this->file, $this->length, $this->delimiter);
		}
		
	    } 

	 public function __destruct() 
	    { 
		if ($this->file) 
		{ 
		    fclose($this->file); 
		} 
	    } 

	 public function parse() 
	    { 
		if($this->file)
		{
			while (($row = fgetcsv($this->file, $this->length, $this->delimiter)) !== FALSE) 
			{ 
				foreach ($this->headers as $i => $header) 
				{ 
				    $temp[$header] = $row[$i]; 
				} 
				$data[] = $temp; 
			} 
			return $data; 
		}
		
	    } 
}
?>
