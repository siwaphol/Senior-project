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
 
// The print_r response of $as->getAttributes() look something like this:
//Array (
//      [first_name] => Array ( [0] => John )
//      [last_name] => Array ( [0] => Doe )
//      [email] => Array ( [0] => john.doe@webtrafficexchange.com )
//)
         

echo '<h1>Complete</h1>';
echo var_dump($attributes);
//echo $testattr;



