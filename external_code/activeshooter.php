

<!DOCTYPE html>
<head>
<title>Fordham University</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>
var nAgt = navigator.userAgent;
var nameOffset,verOffset,ix;
//alert (nAgt);
var x=document.cookie;
//document.write (x + "\n");
</script>

<link href="/site/styles/generic/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/site/styles/generic/site-css.min.css">

<?php 
//test for CAS login cookie 
$loggedin = false; 
$ckie = ""; 
foreach ($_COOKIE as $key=>$val)
  {
    $ckie .= 'name: ' .$_COOKIE['name'] . " " .$key.' is '.$val."<br>\n";
  }
if ( strpos($ckie,"loginfordhamedu") > 0 || strpos($ckie,"fordham.edu") > 0 ){
//if ( strpos($ckie,"loginfordhamedu") > 0  ){
$loggedin = true;
}

if (!$loggedin){
?>

<script>
$(document).ready(function(){
$("#submit").click(function(){
    $("#loading").show();
    $("#caslogin").hide();

//if ((verOffset=nAgt.indexOf("Chrome")) !=-1 ||(verOffset=nAgt.indexOf("Safari")) !=-1   || (verOffset=nAgt.indexOf("MSIE")) !=-1 )){
if ((verOffset=nAgt.indexOf("Firefox")) <= 0 ){
  $("#ac",parent.document.body).hide();
}

});

$(window).load(function() {
    // page is fully loaded, including all frames, objects and images
    $("#loading").hide();
});

});
</script>
<?php }
else
{
?>
<script>
$(document).ready(function(){
$(window).load(function() {
    // page is fully loaded, including all frames, objects and images
    
    $("#loading").hide();
    $("#caslogin").hide();
    $("#mediaspace").show();
    $("#ac",parent.document.body).show();
});

});
</script>

<?php
}
?>
</head>
<body>
<h1>Shots Fired on Campus</h1>
<div id="loading">
<img src="/images/support-loading.gif"  width="240" alt="loading..." />
</div>

<div id="caslogin">
<?php   
//
// phpCAS simple client
//      
// import phpCAS lib
include_once('/var/www/jadu/jadu/custom/CAS.php');

phpCAS::setDebug('/var/www/jadu/logs/cas_log');

// initialize phpCAS
phpCAS::client(CAS_VERSION_2_0, 'login.fordham.edu', 443, '/cas');
//phpCAS::setCasServerCACert('/etc/openldap/certs/cas.pem');           


phpCAS::setNoCasServerValidation();          
if (isset($_REQUEST['logout'])) {
    phpCAS::logout();
}

if (isset($_REQUEST['login'])) {
phpCAS::forceAuthentication();
}

// check CAS authentication
$auth = phpCAS::checkAuthentication();
$isauth =  phpCAS::isSessionAuthenticated();
// for this test, simply print that the authentication was successful
?>
</div>
<?php

if ($auth ==  true) {
    // for this test, simply print that the authentication was successfull
?>

<style>
#mediaspace{margin-top:2em;}
</style>

<div id="mediaspace">
<script src="http://jwpsrv.com/library/38+OOiIIEeOLRiIACusDuQ.js"></script>
<div id="myElement1">Loading the player...</div>
        <script type="text/javascript">
                jwplayer("myElement1").setup({
                file: "http://video.fordham.edu/video/Shots_Fired_on_Campus_V1_Non-Captioned.mp4",
                height:240,
                 image: "http://www.library.fordham.edu/jwplayer/poster_fordham.jpg",
                width:320,
                ga: {}
                });
        </script>
</span>
</div>

<?php
}
else
{
?>

<div class="content">
<br />You need to log in with your University credentials in order to see this page<br />
<br />
<form  id=theform name=login method=post action=https://login.fordham.edu/cas/login>
<label for="username">AccessIT ID: </label><input name=username type=text><br />
<label for="password">Password:</label> <input name=password type=password>
<input name=service type=hidden value="http://<?php echo $_SERVER['SERVER_NAME']; ?>/site/custom_scripts/activeshooter.php">
<input name=auto type=hidden value=true>
<input name=submit id=submit type=submit value=Submit>
</form>

</div>
<?php

}

?>
<div>

</body>
</html>

