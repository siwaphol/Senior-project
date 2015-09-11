<?php
include('testgetcall.php');
    class sspmod_mymodule_Auth_Source_MyAuth extends sspmod_core_Auth_UserPassBase {
        protected function login($username, $password) {
            //if ($username !== 'theusername' || $password !== 'thepassword') {
            $email_authen_result = authen_with_ITSC_api($username,$password);
            if ($email_authen_result==NULL){
                throw new SimpleSAML_Error_Error('WRONGUSERPASS');
            }
            $student_info = get_student_info($email_authen_result->ticket->userName,$email_authen_result->ticket->access_token);
            
            return array(
                'uid' => array('theusername',$email_authen_result->ticket->userName),
                'testattr' => array($student_info->student->id,$student_info->student->firstName->en_Us),
                'displayName' => array('Some Random User'),
                'eduPersonAffiliation' => array('member', 'employee'),
            );
        }


    }