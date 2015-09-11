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

        if($_SESSION['SPerror']!=""){
            throw new SimpleSAML_Error_Error('NOUSERINSP');
            $_SESSION['SPerror']="";
        }

        $email_authen_result = authen_with_ITSC_api($username,$password);

        if ($email_authen_result==NULL || $email_authen_result->success == false){
            throw new SimpleSAML_Error_Error('WRONGUSERPASS');
        }

        $user = "admin";
        $pass = "R71lMJUU";
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=authenmodule;charset=utf8', $user, $pass);
            $query = $dbh->prepare("SELECT * from Users where username like '$username' "); 
            $query->execute();
            $query->setAttribute(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error!: " . $e->getMessage());
            die();
        }

        //There is the username in database
        if($query->rowCount()>0)
        {
            $row = $query->fetch();
            
            $return_username = $row['username'][0];
            $return_id = $row['studentid'][0];
            $return_firstNameEN = $row['firstnameEN'][0];
            $return_lastNameEN = $row['lastnameEN'][0];
            $return_firstNameTH = $row['firstnameTH'][0];
            $return_lastNameTH = $row['lastnameTH'][0];
            $return_email = $row['email'][0];
            $return_facultyEN = $row['facultyEN'][0];
            $return_facultyTH = $row['facultyTH'][0];
            $return_role = $row['role'][0];
        }
        else
        {
            $student_info = get_student_info($email_authen_result->ticket->userName,$email_authen_result->ticket->access_token);
            
            if ($student_info==NULL || $student_info->success ==false){
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
        $dbh = null;

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
