<?php
namespace App;
/**
 * Process a CSV file as an input and parse file data is to be
 * inserted into a MySQL database
 *
 * @author     dthau120391@gmail.com
 * @version    1.0
 */
include "UserUploader.php";


$userUploader = new UserUploader;
$userUploader->run($argv);
?>
