<?php
include('JsonHandler.php');
function authen_with_ITSC_api($user_name,$password){
	// *** $email should not contains @cmu.ac.th ***
	//IMPORTANT
	$url="https://account.cmu.ac.th/v1/api/validateUser?appId=575cb268-e295-40fc-91f3-69e7d239dc24&appSecret=m4Fg4d8ePk&user=" .$user_name."&pw=".$password;
	//$url.='&user='.;
	//  Initiate curl
	$ch = curl_init();
	// Disable SSL verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// Set the url
	curl_setopt($ch, CURLOPT_URL,$url);
	// Execute
	$result=curl_exec($ch);
	// Closing
	curl_close($ch);

	return JsonHandler::decode($result); // Return OOP result 

}
function get_student_info($user_name,$access_token){
	//IMPORTANT must be "accees_token" not access_token in url GET parameter
	$url="https://account.cmu.ac.th/v1/api/Students/" . $user_name . "?appId=575cb268-e295-40fc-91f3-69e7d239dc24&appSecret=m4Fg4d8ePk&accees_token=" . $access_token;
	

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	curl_close($ch);

	return JsonHandler::decode($result);
}
function close_access_token($user_name,$access_token){
	//IMPORTANT must be "accees_token" not access_token in url GET parameter
	$url="https://account.cmu.ac.th/v1/api/Logout/" . $user_name . "?appId=575cb268-e295-40fc-91f3-69e7d239dc24&appSecret=m4Fg4d8ePk&accees_token=" . $access_token;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	curl_close($ch);

	return json_decode($result);
}

?>