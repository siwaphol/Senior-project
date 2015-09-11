<?php
if($_POST)
{
	include("connect.php");

    $to_email       = "";
   
    //check if its an ajax request, exit if not
//    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
//       
////        $output = json_encode(array( //create JSON data
////            'type'=>'error',
////            'text' => 'Sorry Request must be Ajax POST'
////        ));
////        die($output); //exit script outputting json data
//        echo "Sorry Request must be Ajax POST";
//    }
   
    //Sanitize input data using PHP filter_var().
    $username      = $_POST['username'];
   	$sql = "select email from Users where username='$username'";
   	$result = mysql_query($sql);
	if (mysql_num_rows($result) == "1" ){
		$row = mysql_fetch_array($result);
		$to_email = $row['email'];
	}
	else{
//		$output = json_encode(array(
//            'type'=>'error',
//            'text' => 'Not found this username'
//        ));
//        die($output);
        echo "notfound username";
	}

	$subject = "Your password";
	$message = "This is your password AGBEmeAL";
	$from = "admin@mail.authenmodule.com";
	$headers = "From:" . $from;
   
    $send_mail = mail($to_email, $subject, $message, $headers);
   
    if(!$send_mail)
    {
//        //If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
//        $output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
//        die($output);
        echo "Could not send mail!";
    }else{
//        $output = json_encode(array('type'=>'message', 'text' => 'Hi '.$user_name .' Thank you for your email'));
//        die($output);
        echo "Complete Sendmail";
    }

    mysql_close();
}else{
//	$output = json_encode(array('type'=>'error', 'text' => 'Not POST'));
//        die($output);
    echo "Not POST";
}

?>
