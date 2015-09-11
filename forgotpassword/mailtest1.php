<?php
	
	$to_email = "siwaphol.boonpan@gmail.com";
	$subject = "Your password";
	$message = "This is your password AGBEmeAL";
	$from = "admin@mail.authenmodule.com";
	$headers = "From:" . $from;
   
    mail($to_email, $subject, $message, $headers);
   
?>
