<?php

$username = $_POST["username"];

$con = mysql_connect("localhost","admin","R71lMJUU");
mysql_select_db("authenmodule", $con);

class Users
{
	function getPassword($user_name){

		$result = mysql_query("SELECT pass FROM Users WHERE username like '$user_name';");
		$row = mysql_fetch_array($result);

		if(!$row){
			echo "0|No user \"$user_name\"";
			mysql_close($con);
			exit();
		}

		$resultarray = $row['pass'];

		return $resultarray; 
	}

	function getEmail($user_name){

		$result = mysql_query("SELECT email FROM Users WHERE username like '$user_name';");
		$row = mysql_fetch_array($result);

		if(!$row){
			echo "0|No user \"$user_name\"";
			mysql_close($con);
			exit();
		}
		$email = $row['email'];

		return $email;
	}
}

$class = new Users();
$method =  $class->getPassword($username);
// echo json_encode($method);

$to_email = $class->getEmail($username);
$subject = "Your password";
$message = "This is your password : " . $method;
$from = "admin@mail.authenmodule.com";
$headers = "From: " . $from;

$sendmail = mail($to_email, $subject, $message, $headers);
if(!$sendmail){
	echo "0|Cannot send email to $to_email";
}else{
	echo "1|Your password was sent";
}

mysql_close($con);