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
    if($as->isAuthenticated()){
        $as->logout('https://202.28.24.215/testloginform.php');
    }

    
    // $as->logout('');

} catch (Exception $e) {
    // SimpleSAMLphp is not configured correctly.
    throw(new Exception("SSO Logout failed: ". $e->getMessage()));
    return;
}

?>
