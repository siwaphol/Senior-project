<?php
/**
 * SAML 2.0 remote SP metadata for simpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote
 */

/*
 * Example simpleSAMLphp SAML 2.0 SP
 */
// $metadata['https://202.28.24.215/simplesaml/module.php/saml/sp/metadata.php/default-sp'] = array (
//   'SingleLogoutService' => 'https://202.28.24.215/simplesaml/module.php/saml/sp/saml2-logout.php/default-sp',
//   'AssertionConsumerService' => 'https://202.28.24.215/simplesaml/module.php/saml/sp/saml2-acs.php/default-sp',
// );
$metadata['https://202.28.24.215/simplesaml/module.php/saml/sp/metadata.php/default-sp'] = array (
  'SingleLogoutService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://202.28.24.215/simplesaml/module.php/saml/sp/saml2-logout.php/default-sp',
    ),
  ),
  'AssertionConsumerService' => 
  array (
    0 => 
    array (
      'index' => 0,
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
      'Location' => 'https://202.28.24.215/simplesaml/module.php/saml/sp/saml2-acs.php/default-sp',
    ),
    1 => 
    array (
      'index' => 1,
      'Binding' => 'urn:oasis:names:tc:SAML:1.0:profiles:browser-post',
      'Location' => 'https://202.28.24.215/simplesaml/module.php/saml/sp/saml1-acs.php/default-sp',
    ),
    2 => 
    array (
      'index' => 2,
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact',
      'Location' => 'https://202.28.24.215/simplesaml/module.php/saml/sp/saml2-acs.php/default-sp',
    ),
    3 => 
    array (
      'index' => 3,
      'Binding' => 'urn:oasis:names:tc:SAML:1.0:profiles:artifact-01',
      'Location' => 'https://202.28.24.215/simplesaml/module.php/saml/sp/saml1-acs.php/default-sp/artifact',
    ),
  ),
  // 'certData' => 'MIID1TCCAr2gAwIBAgIJANEgfdeZ+fcCMA0GCSqGSIb3DQEBCwUAMIGAMQswCQYDVQQGEwJUSDETMBEGA1UECAwKQ2hpYW5nIE1haTEPMA0GA1UEBwwGU3V0aGVwMQswCQYDVQQKDAJDUzELMAkGA1UECwwCQ1MxCzAJBgNVBAMMAkNTMSQwIgYJKoZIhvcNAQkBFhVub25nLXNpd2FAaG90bWFpbC5jb20wHhcNMTUwMjIxMDYzMDQ1WhcNMjUwMjIwMDYzMDQ1WjCBgDELMAkGA1UEBhMCVEgxEzARBgNVBAgMCkNoaWFuZyBNYWkxDzANBgNVBAcMBlN1dGhlcDELMAkGA1UECgwCQ1MxCzAJBgNVBAsMAkNTMQswCQYDVQQDDAJDUzEkMCIGCSqGSIb3DQEJARYVbm9uZy1zaXdhQGhvdG1haWwuY29tMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxOrI7EyA3OzXHHiiJBkxL/8VpjWe2/ccEIIJ4T3yedqUmrI6Q4g0tINOQb3IxG47kStJtybwIILXf+/+TVwrtVoLTQl6ezr4N2Yk6uW4hGZDU01Icn1jb5dIPc2zMsAfcXfpSdM6CRJQOG4BR29duUSsVpfJyTVSYVbxvMxCvSgxyRMFULkSYpBvT2HF7j2yxfaV6PIY1RzyShvuHjU46oEZzNtww3PnzNL8RZAdxX+HmwtMJ2GTS/Y9l0yYkDhmCHquDvRfDhH9pi3WiWfh98bH/56+QH8+b8kQJLXfCvHH8E285H+ISxumMiLL9Di1V/gawUNoeiDTRxVsKHhErwIDAQABo1AwTjAdBgNVHQ4EFgQUKUA2Tm93esfC4Y8L9o5ycoDFqaswHwYDVR0jBBgwFoAUKUA2Tm93esfC4Y8L9o5ycoDFqaswDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAF0wrVgSIjy/x6zKOkn3PzHsbk9VDoEAM7BADWPpvpLWaKGZbJ7jX9ecSFMo70GELUPHwR5qd1p283GAiePThI/KO12Ctqfr2yqJN7otCBTUdi9Um6Ar9L9dcXsppcaHytAMlRwiR1wrRoshj6YlyVkP8fULKwazTPWDS5h+BVZz2Z0cLwmOvORTpfKu9i5+mkvTOuXSGaqZ+1LHo9NkP3DhZ1+udlTF5z9VtcFjnLqjrKgA+H6oj3HqdZj51Pl01XgTW9GwUj5M0ZiMROizzc9bzTA14MFH++hLQv/kc2uAa+g8xKp6Wz1Ukocz+jkvaQi7nKQhNo2wjQE/0QKVOFw==',
);

/*
 * This example shows an example config that works with Google Apps for education.
 * What is important is that you have an attribute in your IdP that maps to the local part of the email address
 * at Google Apps. In example, if your google account is foo.com, and you have a user that has an email john@foo.com, then you
 * must set the simplesaml.nameidattribute to be the name of an attribute that for this user has the value of 'john'.
 */
$metadata['google.com'] = array(
	'AssertionConsumerService' => 'https://www.google.com/a/g.feide.no/acs',
	'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
	'simplesaml.nameidattribute' => 'uid',
	'simplesaml.attributes' => FALSE,
);
