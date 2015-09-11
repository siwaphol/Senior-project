<?php
include("connectdb.php");
$test="select * form user";
$resulttest=mysql_query($test) ;
$resulttest1=mysql_fetch_array($resulttest);
//echo $resulttest;
while(mysql_fetch_array($resulttest1)){
	echo "name=".$resulttest1["username"];
	echo "password=".$resulttest1["password"];

}

?>