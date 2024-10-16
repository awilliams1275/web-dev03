<style>
.off-canvas-wrap{opacity:.00;}
body{background-image:url("/images/support-loading.gif");
background-repeat:no-repeat;}
</style>
<?php
class jsCheck {

	public function __construct() {
		if(!isset($_SESSION)) {
			session_start();
		}
		if(!isset($_SESSION['javascript'])) {
			$_SESSION['javascript'] = false;
		}
		if(!isset($_SESSION['loaded'])) {
			$_SESSION['loaded'] = 1;
		}
	}
	
	public $js = false;
	
	public function checkJsByCookies() {
		if($_SESSION['loaded'] < 2) {
			if(isset($_COOKIE['javascript']) && $_COOKIE['javascript'] == true) {
				$_SESSION['javascript'] = true;
			}
			if($_SESSION['javascript'] == false) {
	
echo <<<JS
	<script type="text/javascript">
		document.cookie = "javascript=true";
	</script>
JS;
			$_SESSION['loaded']++;
			}
		}
		$this->js = $_SESSION['javascript'];
	}

	public function checkJsByForm() {
		if(isset($_POST['javascript'])) {
			$_SESSION['javascript'] = true;
		}
		if($_SESSION['loaded'] < 2 && $_SESSION['javascript'] != true) {
			$_SESSION['loaded']++;
echo <<<JS
	<form name="javascript" id="javascript" method="post" style="display:none">
		<input name="javascript" type="text" value="true" />
		<script type="text/javascript">
			document.javascript.submit();
		</script>
	</form>
JS;
		}
		$this->js = $_SESSION['javascript'];
	}

	public function isJsActivated() {
		if($this->js == true || $_SESSION['javascript'] == true) {
			return true;
		}
		else {
			return false;
		}
	}
}
# This example is using the form-methond through checkJsByForm()
$js = new jsCheck;
$js->checkJsByForm();

if($js->isJsActivated() === true) {
# After the call to checkJsByForm() is done we use the function isJsActivated to see if JS is activated or not.
# The function returns true if activated and false otherwise.
        echo "JavaScript is activated!";
}
else {
?>
<style>
.off-canvas-wrap{opacity:.00;}
body{background-image:url("/images/support-loading.gif");
background-repeat:no-repeat;}
</style>

<?php 
}
?>

<style>
#casimg{
position:absolute;
top:-5em;
left:5em;
z-index:100 !important;
background-color:#fffff;
width:100%;
opacity:.9;
}

</style>


<script>
var getQueryString = function ( field, url ) {
    var href = url ? url : window.location.href;
    var reg = new RegExp( '[?&]' + field + '=([^&#]*)', 'i' );
    var string = reg.exec(href);
    return string ? string[1] : null;
};

 function getCookie(name)
  {
    var re = new RegExp(name + "=([^;]+)");
    var value = re.exec(document.cookie);
    return (value != null) ? unescape(value[1]) : null;
  }

var ticket = getQueryString('ticket');
//if qs exists, then set cookie
if (ticket){
document.cookie='ticketjaduuat='+ ticket; 
document.write('<style>.off-canvas-wrap\{opacity:1\;\}body\{background-image:url("")\;</style>');
}
//if not then redirect
else{
document.write('<style>.off-canvas-wrap\{opacity:.00\;\}body\{background-image:url("/images/support-loading.gif")\;background-repeat:no-repeat\;\}</style>');

window.location='http://jaduuat.fordham.edu/site/custom_scripts/caspreredir.php?service=http://jaduuat.fordham.edu/homepage/2674/admin_test_home';

}
</script>