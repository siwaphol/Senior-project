<?php

$database_name="project499";
$database_username="admin";
$database_password="";

$connection = mysql_connect("localhost",$database_username,$database_password);

if(!$connection) {
    echo "Cannot connect to the database – incorrect details";
} else{
    mysql_select_db($database_name);
    $result=mysql_query("show tables");

    while($tables = mysql_fetch_array($result)) {

        foreach ($tables as $key => $value) {
            mysql_query("ALTER TABLE ".$value." CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        }
    }
echo "Successfull collation change!"; 
}
?>