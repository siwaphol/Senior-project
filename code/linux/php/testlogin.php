<?php

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

echo '<h1>Complete</h1>';
echo var_dump($attributes);

function throw_SP_no_user($localas){
    try {
        if ($localas->isAuthenticated()) {
            $_SESSION['SPerror'] = "No user on Service Provider";
            $localas->logout('https://202.28.24.215/testloginform.php');
        }
    }catch(Exception $e){
        throw(new Exception("SSO Logout failed: ". $e->getMessage()));
        return;
    };
}
//public function throw_no_SP_user($as){
//    try {
//        if($as->isAuthenticated()){
//            $_SESSION['SPerror'] = "No user on Service Provider";
//            $as->logout('https://202.28.24.215/testloginform.php');
//        }
//
//    } catch (Exception $e) {
//        throw(new Exception("SSO Logout failed: ". $e->getMessage()));
//        return;
//    };
//}




