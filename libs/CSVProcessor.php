<?php
namespace App;

/**
 * Process a CSV file as an input and parse file data
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

    /**
     * CSVProcessor constructor.
     *
     * @param $filePath
     * @param string $delimiter
     * @param int $length
     */
    public function __construct($filePath, $delimiter = ",", $length = 1000)
    {
        $this->file = fopen($filePath, "r");
        $this->delimiter = $delimiter;
        $this->length = $length;

        if ($this->file) {
            $this->headers = fgetcsv($this->file, $this->length, $this->delimiter);
        }
    }

    /**
     * Get headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Destroy CSVProcessor instance
     */
    public function __destruct()
    {
        if ($this->file) {
            fclose($this->file);
        }
    }

    /**
     * Parse data from csv file
     *
     * @return mixed
     */
    public function parse()
    {
        if ($this->file) {
            while (($row = fgetcsv($this->file, $this->length, $this->delimiter)) !== FALSE) {
                $temp = null;
                foreach ($this->headers as $i => $header) {
                    $temp[trim($header)] = trim($row[$i]);
                }
                $this->data[] = $temp;
            }
            return $this->data;
        }

    }
}
?>
