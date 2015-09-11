<?php
include('testgetcall.php');
class sspmod_mymodule_Auth_Source_MyAuth extends sspmod_core_Auth_UserPassBase {

    private $return_username;
    private $return_id;
    private $return_firstNameEN;
    private $return_lastNameEN;
    private $return_firstNameTH;
    private $return_lastNameTH;
    private $return_email;
    private $return_facultyEN;
    private $return_facultyTH;
    private $return_role;
        //SimpleSAMLphp always send in $username and password to login function
    protected function login($username, $password) {

        if($_SESSION['SPerror']!="" || $_SESSION['SPerror']!=null){
            throw new SimpleSAML_Error_Error('NOUSERINSP');
            $_SESSION['SPerror']="";
        }


        $user = "admin";
        $pass = "R71lMJUU";
        try {
            $mysqli = mysqli_connect("localhost",$user,$pass,"authenmodule");
            if ($mysqli->connect_errno) {
                echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            }
            $mysqli->set_charset("utf8");

        } catch (Exception $e) {
            throw new Exception("Error!: " . $e->getMessage());
            die();
        }

        //There is the student username (firstname_lastname) in database
        if(preg_match('/^[a-z]+_[a-z]+$/i', $username)) {
            try {
                $sql = "SELECT * from Users where username='$username' ";
                $result = $mysqli->query($sql);
            }catch (Exception $e) {
                throw new Exception("Error!: " . $e->getMessage());
                die();
            }
            $email_authen_result = authen_with_ITSC_api($username,$password);
            if ($email_authen_result==NULL || $email_authen_result->success == false){
                throw new SimpleSAML_Error_Error('WRONGUSERPASS');
            }

            if ($result->num_rows >= 1) {
                $row = $result->fetch_array();

                $return_username = $row['username'];
                $return_id = $row['studentid'];
                $return_firstNameEN = $row['firstnameEN'];
                $return_lastNameEN = $row['lastnameEN'];
                $return_firstNameTH = $row['firstnameTH'];
                $return_lastNameTH = $row['lastnameTH'];
                $return_email = $row['email'];
                $return_facultyEN = $row['facultyEN'];
                $return_facultyTH = $row['facultyTH'];
                $return_role = $row['role'];
            } else {
                $student_info = get_student_info($email_authen_result->ticket->userName, $email_authen_result->ticket->access_token);

                if ($student_info == NULL || $student_info->success == false) {
                    throw new SimpleSAML_Error_Error('WRONGUSERPASS');
                }

                $return_username = $email_authen_result->ticket->userName;
                $return_id = $student_info->student->id;
                $return_firstNameEN = $student_info->student->firstName->en_US;
                $return_lastNameEN = $student_info->student->lastName->en_US;
                $return_firstNameTH = $student_info->student->firstName->th_TH;
                $return_lastNameTH = $student_info->student->lastName->th_TH;
                $return_email = $email_authen_result->ticket->userName . "@cmu.ac.th";
                $return_facultyEN = $student_info->student->faculty->en_US;
                $return_facultyTH = $student_info->student->faculty->th_TH;
                $return_role = "NU";
            }
        }else{//in case user is not student("firstname_lastname") can be admin,teacher and ta
            try {
                $sql = "SELECT * from Users where username='$username' and pass='$password' and role!='ST'";
                $result = $mysqli->query($sql);
            }catch (Exception $e) {
                throw new Exception("Error!: " . $e->getMessage());
                die();
            }

            if ($result->num_rows >= 1){
                $row = $result->fetch_array();

                $return_username = $row['username'];
                $return_id = $row['studentid'];
                $return_firstNameEN = $row['firstnameEN'];
                $return_lastNameEN = $row['lastnameEN'];
                $return_firstNameTH = $row['firstnameTH'];
                $return_lastNameTH = $row['lastnameTH'];
                $return_email = $row['email'];
                $return_facultyEN = $row['facultyEN'];
                $return_facultyTH = $row['facultyTH'];
                $return_role = $row['role'];
            }else{//1.no user in database or 2.user provide wrong password
                try {
                    $sql = "SELECT * from Users where username='$username' and role!='ST'";
                    $result = $mysqli->query($sql);
                }catch (Exception $e) {
                    throw new Exception("Error!: " . $e->getMessage());
                    die();
                }
                if ($result->num_rows >= 1){
                    throw new SimpleSAML_Error_Error(array('USERFORGOTPASSWORD','%URL%' => "http://202.28.24.215/myshop/forgotpassword/index.php"));
                } else{
                    throw new SimpleSAML_Error_Error('WRONGUSERPASS');
                }
            }
        }


//        $dbh = null;
        $mysqli->close();
        return array(
            'username' => array($return_username),
            'id' => array($return_id),
            'firstNameEN' => array($return_firstNameEN),
            'firstNameTH' => array($return_firstNameTH),
            'lastNameEN' => array($return_lastNameEN),
            'lastNameTH' => array($return_lastNameTH),
            'facultyEN' => array($return_facultyEN),
            'facultyTH' => array($return_facultyTH),
            'email' => array($return_email),
            'role' => array($return_role),
            );
    }


}
