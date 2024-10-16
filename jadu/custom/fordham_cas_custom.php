<?php
//
// phpCAS simple client
//
// import phpCAS lib
//$service=$_SERVER['HTTP_REFERER'];

//echo "service" .$service;
//echo $_GET['service'];
//echo $_GET['ticket'];
//echo $_SERVER['HTTP_REFERER'];

include_once('/var/www/jadu/jadu/custom/CAS.php');

phpCAS::setDebug('/var/www/jadu/logs/cas_log');

// initialize phpCAS
phpCAS::client(CAS_VERSION_2_0, 'loginp.fordham.edu', 443, '/cas');
//phpCAS::setCasServerCACert('/etc/openldap/certs/cas.pem');

phpCAS::setNoCasServerValidation();
if (isset($_REQUEST['logout'])) {
    phpCAS::logout();
}

//if (isset($_REQUEST['login'])) {
phpCAS::forceAuthentication();
//}

// check CAS authentication
$auth = phpCAS::checkAuthentication();
$isauth =  phpCAS::isSessionAuthenticated();
// for this test, simply print that the authentication was successful
//echo "auth" .$auth;

//if($auth || $_GET['ticket'].length > 0 || isset($_COOKIE['ticketjaduuat']))
if (!$auth)
{
header('Location: https://loginp.fordham.edu?service='.$fordham_rf);
//echo $service;
//}
//else
//{
//header('Location: '.$service );
}

?>

