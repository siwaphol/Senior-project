<?php
session_start();

include("connect.php");
$user = $_POST['user'];
$pass = $_POST['pass'];
// check User Status Switch Case
$person = $_POST['select'];
switch ($person){
// student
case "student" : 
	$sql = "select * from student where studentId='$user' and studentPw='$pass'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) == "1" ){
		$row = mysql_fetch_array($result);
		$_SESSION['ssname'] = $row['studentName'];
		$_SESSION['user']  = $row['studentId'];
		$_SESSION['status'] = "student" ;
		echo "student";
		}
	mysql_close();
	break;
//teacher
case "teacher" : 
	$sql = "select * from teacher where teacherId='$user' and teacherPw='$pass'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) == "1" ){
		$row = mysql_fetch_array($result);
		$_SESSION['ssname'] = $row['teacherName'];
		$_SESSION['user'] = $row['teacherId'];
		$_SESSION['status'] = "teacher" ;
		echo "teacher";
		}
	mysql_close();
	break;
//admin
case "admin" :  
	$sql = "select adminId,adminName from admin where adminId='$user' and adminPw='$pass'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) == "1" ){
		$row = mysql_fetch_array($result);
		$_SESSION['ssname'] = $row['adminName'];
		$_SESSION['user'] = $row['adminId'];
		$_SESSION['status'] = "admin" ;
		echo "admin";
		}
	mysql_close();
	break;
//ta
case "ta" :  
	$sql = "select * from ta where taId='$user' and taPw='$pass'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) == "1" ){
		$row = mysql_fetch_array($result);
		$_SESSION['ssname'] = $row['taName'];
		$_SESSION['user'] = $row['taId'];
		$_SESSION['status'] = "ta" ;
		echo "ta";
		}
	mysql_close();
	break;
}
			
?>