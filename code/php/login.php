<?php
session_start();

include("connect.php");
$user = $_POST['user'];
$pass = $_POST['pass'];


$lib = "../simplesamlphp/lib";
$sp = "default-sp";  // Name of SP defined in config/authsources.php
 
try {
    // Autoload simplesamlphp classes.
    if(!file_exists("{$lib}/_autoload.php")) {
        throw(new Exception("simpleSAMLphp lib loader file does not exist: ".
        "{$lib}/_autoload.php"));
    }
 
    include_once("{$lib}/_autoload.php");
    $as = new SimpleSAML_Auth_Simple($sp);
 	
    // Take the user to IdP and authenticate.
    $as->requireAuth();
    $valid_saml_session = $as->isAuthenticated();
 
} catch (Exception $e) {
    // SimpleSAMLphp is not configured correctly.
    throw(new Exception("SSO authentication failed: ". $e->getMessage()));
    return;
}
 
if (!$valid_saml_session) {
    // Not valid session. Redirect a user to Identity Provider
    try {
        $as = new SimpleSAML_Auth_Simple($sp);
        $as->requireAuth();
    } catch (Exception $e) {
        // SimpleSAMLphp is not configured correctly.
        throw(new Exception("SSO authentication failed: ". $e->getMessage()));
        return;
    }
}
 
// At this point, the user is authenticated by the Identity Provider, and has access
// to the attributes received with SAML assertion.
$attributes = $as->getAttributes();
 
// The print_r response of $as->getAttributes() look something like this:
//Array (
//      [first_name] => Array ( [0] => John )
//      [last_name] => Array ( [0] => Doe )
//      [email] => Array ( [0] => john.doe@webtrafficexchange.com )
//)
         

echo '<h1>Complete</h1>';
echo var_dump($attributes);
//echo $testattr;
// check User Status Switch Case

//แบบใหม่
$id=$attributes->id[0];
$sql = "select * from student where studentId='$id' ";
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






// แบบเก่า
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