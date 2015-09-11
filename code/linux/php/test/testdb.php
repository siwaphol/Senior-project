<?php
include("../connect.php");
$test="SELECT studentid as id FROM  student";
$resulttest=mysql_query($test) or die(mysql_error());

//echo $resulttest;
while($resulttest1=mysql_fetch_array($resulttest)){
	echo "name=".$resulttest1["id"];
	//echo "password=".$resulttest1["studentpw"];

}

?>