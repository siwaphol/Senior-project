<?php
if(!extension_loaded('radius')) {

    if (preg_match('/windows/i', getenv('OS'))) {
        dl('php_radius.dll');
    } else {
        dl('radius.so');
    }

}
$module = 'radius';
$functions = get_extension_funcs($module);
//echo "Functions available in the test extension:<br>\n";
//foreach($functions as $func) echo $func . "<br>\n";


session_start();


include("connect.php");
$user_email = $_POST['user_email'] . "@cmu.ac.th";
$user_pass = $_POST['user_pass'];
// check User Status Switch Case
$person = $_POST['select'];


$sql = "select * from student where email='$user_email'";
$result = mysql_query($sql);
if (mysql_num_rows($result) == "1" ){
	$row = mysql_fetch_array($result);
	$_SESSION['ssname'] = $row['studentName'];
	$_SESSION['user']  = $row['studentId'];
	$_SESSION['status'] = "student" ;
	//echo "student";
	}
mysql_close();



//$username = 'siwaphol_boonpan@cmu.ac.th';
//$password = 'siwaphol01';
$username = 'dummy username';
$password = 'dummy passowrd';
$username = $user_email;
$password = $user_pass;
$radserver = 'authen.cm.edu';
$radport = 1812;
$sharedsecret = 'AXNK95sEQT';
$auth_type = 'pap';
//$auth_type = 'chap';
//$auth_type = 'mschapv1';
//$auth_type = 'mschapv2';

//echo "=======================";
$res = radius_auth_open()
	or die ("Could not create handle");
//echo "$res Radius authe open succesfully<br>\n";

//if (!radius_config($res, '/etc/radius.conf')) {
/*if (!radius_config($res, 'D:/php-devel/pear/PECL/radius/radius.conf')) {
 echo 'RadiusError:' . radius_strerror($res). "\n<br>";
 exit;
}*/


if (!radius_add_server($res, $radserver, $radport, $sharedsecret, 3, 3)) {
    echo 'RadiusError:' . radius_strerror($res). "\n<br>";
    exit;
}

if (!radius_add_server($res, $radserver, $radport, $sharedsecret, 3, 3)) {
    echo 'RadiusError:' . radius_strerror($res). "\n<br>";
    exit;
}

if (!radius_create_request($res, RADIUS_ACCESS_REQUEST)) {
    echo 'RadiusError:' . radius_strerror($res). "\n<br>";
    exit;
}

if (!radius_put_string($res, RADIUS_NAS_IDENTIFIER, isset($HTTP_HOST) ? $HTTP_HOST : 'localhost'))  {
    echo 'RadiusError:' . radius_strerror($res). "\n<br>";
    exit;
}
 
if (!radius_put_int($res, RADIUS_SERVICE_TYPE, RADIUS_FRAMED)) {
    echo 'RadiusError:' . radius_strerror($res). "\n<br>";
    exit;
}
  
if (!radius_put_int($res, RADIUS_FRAMED_PROTOCOL, RADIUS_PPP)) {
    echo 'RadiusError:' . radius_strerror($res). "\n<br>";
    exit;
}

if (!radius_put_string($res, RADIUS_CALLING_STATION_ID, isset($REMOTE_HOST) ? $REMOTE_HOST : '127.0.0.1') == -1) {
    echo 'RadiusError:' . radius_strerror($res). "\n<br>";
    exit;
}

if (!radius_put_string($res, RADIUS_USER_NAME, $username)) {
    echo 'RadiusError:' . radius_strerror($res). "\n<br>";
    exit;
}

//Check if PAP protocol is ok
//echo "PAP<br>\n";
if (!radius_put_string($res, RADIUS_USER_PASSWORD, $password)) {
    echo 'RadiusError:' . radius_strerror($res). "<br>\n";
    exit;
}
//*********************

if (!radius_put_int($res, RADIUS_SERVICE_TYPE, RADIUS_FRAMED)) {
    echo 'RadiusError:' . radius_strerror($res). "\n<br>";
    exit;
}

if (!radius_put_int($res, RADIUS_FRAMED_PROTOCOL, RADIUS_PPP)) {
    echo 'RadiusError:' . radius_strerror($res). "\n<br>";
    exit;
}

$req = radius_send_request($res);
if (!$req) {
    echo 'RadiusError:' . radius_strerror($res). "\n<br>";
    exit;
}
// ================= Checking area whether the auth is complete
switch($req) {
case RADIUS_ACCESS_ACCEPT:
        //echo "Radius Request accepted<br>\n";
	echo "student";
    break;

case RADIUS_ACCESS_REJECT:
    echo "Radius Request rejected with username and password" . $username . $password;
    break;

default:
    echo "Unexpected return value:$req\n<br>";
}

radius_close($res);
			
?>
