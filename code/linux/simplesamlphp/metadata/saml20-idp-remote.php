<?php
/**
 * SAML 2.0 remote IdP metadata for simpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-remote 
 */

/*
 * Guest IdP. allows users to sign up and register. Great for testing!
 */
/**$metadata['https://openidp.feide.no'] = array(
	'name' => array(
		'en' => 'Feide OpenIdP - guest users',
		'no' => 'Feide Gjestebrukere',
	),
	'description'          => 'Here you can login with your account on Feide RnD OpenID. If you do not already have an account on this identity provider, you can create a new one by following the create new account link and follow the instructions.',

	'SingleSignOnService'  => 'https://openidp.feide.no/simplesaml/saml2/idp/SSOService.php',
	'SingleLogoutService'  => 'https://openidp.feide.no/simplesaml/saml2/idp/SingleLogoutService.php',
	'certFingerprint'      => 'c9ed4dfb07caf13fc21e0fec1572047eb8a7a4cb'
);*/

$metadata['https://202.28.24.215/simplesaml/saml2/idp/metadata.php'] = array (
  'metadata-set' => 'saml20-idp-remote',
  'entityid' => 'https://202.28.24.215/simplesaml/saml2/idp/metadata.php',
  'SingleSignOnService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://202.28.24.215/simplesaml/saml2/idp/SSOService.php',
    ),
  ),
  'SingleLogoutService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://202.28.24.215/simplesaml/saml2/idp/SingleLogoutService.php',
    ),
  ),
  'certData' => 'MIID3zCCAsegAwIBAgIJAMWq0a3PwkXzMA0GCSqGSIb3DQEBCwUAMIGFMQswCQYDVQQGEwJUSDETMBEGA1UECAwKQ2hhaW5nIG1haTEOMAwGA1UEBwwFTXVhbmcxDDAKBgNVBAoMA0NNVTELMAkGA1UECwwCQ1MxCzAJBgNVBAMMAkNTMSkwJwYJKoZIhvcNAQkBFhpzaXdhcGhvbC5ib29ucGFuQGdtYWlsLmNvbTAeFw0xNTA1MTAwNDI3MjdaFw0yNTA1MDkwNDI3MjdaMIGFMQswCQYDVQQGEwJUSDETMBEGA1UECAwKQ2hhaW5nIG1haTEOMAwGA1UEBwwFTXVhbmcxDDAKBgNVBAoMA0NNVTELMAkGA1UECwwCQ1MxCzAJBgNVBAMMAkNTMSkwJwYJKoZIhvcNAQkBFhpzaXdhcGhvbC5ib29ucGFuQGdtYWlsLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAMIaM49r52FI/Lu3HWLo1+Bf1MrUcRUQQ4yklrHosbT/QTgCusJy+vEd/J1B+ndc2iZpn4kJUl5RNf09DZupn6RGTb+4CglowlvbuFoxrR95tiEL3ZD26Y4ZYnlhlfDC6pdfU3Wh6W+dGdlWtqOF4UTw2GaKTmOphc2sPZ78NCdmU3YYlRZCCrDqy4WBMP1jOzwGqnP1KvCtghzNx+mwIfTs9PDjiaq3Yu1Xhd/c0nHI1CTctxCqnhzyxfmRtL5iF+7kxzOyfr/gZpFdk0ZTSR2m3FYlTWH9VKSbR0Hje69QoS7SD6kXMqdqC3GD8AAq1pEiBnA2KzKVMLQctN7NCHUCAwEAAaNQME4wHQYDVR0OBBYEFM7a62jiMplrfgbnHzjJtmJLcFeQMB8GA1UdIwQYMBaAFM7a62jiMplrfgbnHzjJtmJLcFeQMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQELBQADggEBADCyBJaWEdKO8bUEM5qvyj6h2S90GJKDPRGtPUalOYkH9gqEDGVm0Jq48y/Wwic7cjOxQRlg8JCMIkfIAyGKKNJMwiqwn1tQJxc415V+3Jh3+oxp8PsiO/E6qWgLaZFpMvEITqFHjl561pAUHGwuIecq8TTRE0bNlNNDEeiJLPfwCIL0jbRGXXAKC/BG1/FIzQbgqF598M7TaoL96RZ1AWnmRlhMQ6fuT4jQSwtopqTAwbJGcYRwWYWgmi3+qGP9lInUXl200hs0VPLStqYZDWoxoUPcxQAK37iOyuFReZtjarAUaPEumFN8GdklJtLL0D+e7H/piqcRUDp53sdifAs=',
  'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',
);

