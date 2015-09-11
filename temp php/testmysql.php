<?php
$dbhost = 'localhost';
$dbuser = 'admin';
$dbpass = 'siwaphol01';

$conn = mysql_connect($dbhost,$dbuser,$dbpass) or die ('Error connecting to mysql');

if($conn) {
echo "CONNECT OK";
}

$dbname = 'mysql';
mysql_select_db($dbname);

?>
