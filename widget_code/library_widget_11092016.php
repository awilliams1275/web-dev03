FRONT-END
---------------------------------------------------------------
<div id="fu_container">
<div id="jumbotron" style="background-image:url('/images/bg4_1.jpg');">
<link rel="stylesheet" href="/site/custom_css/jquery-ui.min.css">
<link rel="stylesheet" href="/site/custom_css/jquery-ui.theme.min.css">
<script src="/site/javascript/fordham/jquery-1.12.4.min.js"></script>
  <script src="/site/javascript/fordham/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/site/custom_css/sirsiform.css">
<?php 
//include_once('websections/JaduHomepageWidgetSettings.php');
include_once('custom/mobile_detect.php');
$detect = new Mobile_Detect;

//---------------------------------------------------
//Get the Tab Head/Headline
function getTabHead($fu_id,$fu_page){
$fu_tab_head = "";

$mainDB = Jadu_Service_Container::getInstance()->getMainDB();
                $query = "SELECT * FROM JaduDocumentPages where documentID =".intval($fu_id) ." AND pageNumber =" . intval($fu_page);
                $result = $mainDB->Execute($query);

if (!$result->EOF) {
 $fu_tab_head  = $result->fields(title);
} else {
$fu_tab_head = "";
}
return $fu_tab_head;
}
//-------------------------------------------------------
//Get the description/content
function getTabData($fu_id,$fu_page){
$fu_response = "";

$mainDB = Jadu_Service_Container::getInstance()->getMainDB();
                $query = "SELECT * FROM JaduDocumentPages where documentID =".intval($fu_id) ." AND pageNumber =" . intval($fu_page);
                $result = $mainDB->Execute($query);

if (!$result->EOF) {
        $fu_response  = $result->fields(description);
} else {
$fu_response = "Invalid Title";
}
return $fu_response;
}
//-------------------------------------------------------
$futabhead1 =  getTabHead(9089,1);
$futabcontent1 = getTabData(9089,1);

$futabhead2 = getTabHead(9089,2);
$futabcontent2 = getTabData(9089,2);

if ( $futabhead2 ){
$futabhead2 = '<li><a href="#tabs-2">'.$futabhead2.'</a></li>';
$futabcontent2 =  '<div id="tabs-2">'.$futabcontent2.'</div>';
}else{
  $futabhead2 = "";
  $futabcontent2 = "";
}

$futabhead3 = getTabHead(9089,3);
$futabcontent3 = getTabData(9089,3);

if ( $futabhead3 ){
$futabhead3 = '<li><a href="#tabs-3">'.$futabhead3.'</a></li>';
$futabcontent3 = '<div id="tabs-3">'.$futabcontent3.'</div>';
}else{
  $futabhead3 = "";
  $futabcontent3 = "";
}

$futabhead4 = getTabHead(9089,4);
$futabcontent4 = getTabData(9089,4);

if ( $futabhead4 ){
$futabhead4 = '<li><a href="#tabs-4">'.$futabhead4.'</a></li>';
$futabcontent4 = '<div id="tabs-4">'.$futabcontent4.'</div>';
}else{
  $futabhead4 = "";
  $futabcontent4 = "";
}

$futabhead5 = getTabHead(9089,6);
$futabcontent5 = getTabData(9089,6);

if ( $futabhead5 ){
$futabhead5 = '<li><a href="#tabs-5">'.$futabhead5.'</a></li>';
$futabcontent5 = '<div id="tabs-5">'.$futabcontent5.'</div>';
}else{
  $futabhead5 = "";
  $futabcontent5 = "";
}

$fu_hours_head = getTabHead(9089,5);
$fuhourscontent = getTabData(9089,5);
?>

<div id="tabs_full">
<script>
    var $i = jQuery.noConflict();
</script> 

<div class="col-md-8" style="padding:10px;">
        <div style="margin:10px;">
        
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">%FU_TAB_HEAD_1%</a></li>
    <?php echo $futabhead2 ?>
    <?php echo $futabhead3 ?>
    <?php echo $futabhead4 ?>
    <?php echo $futabhead5 ?>

  </ul>
<div id="tabs-1"  style="min-height:200px;">
<?php echo $futabcontent1 ?>
</div>

<?php echo $futabcontent2 ?>

<?php echo $futabcontent3 ?>

<?php echo $futabcontent4 ?>

<?php echo $futabcontent5 ?>

        </div><!--tabs-->
      </div><!-- end col-md-8--> 
    </div>
</div><!--end tabs_full-->

<div id="tabs_collapsed"><!--for desktop display < 768px wide -->
 <script>
var $i = jQuery.noConflict();
$i(function() {
    $i( "#accordion" ).accordion({
  active: 5
  });
})
 </script> 
<div id="accordion">
<h3><?php echo $futabhead1 ?></h3>
 <div style="height:10em;min-height:10em;">
    <p><?php echo $futabcontent1 ?></p>
  </div>
  
<h3><?php echo $futabhead2 ?></h3>
  <div style="height:10em;min-height:25em;">
   <?php echo $futabcontent2 ?>
 </div>
  
<h3><?php echo $futabhead3 ?></h3>
  <div style="height:10em;min-height:10em;">
<style>
 .ui-widget button
{color:#000000;background-color:#ffffff;padding:.25em .75em;border:none;}
</style>
 <?php echo $futabcontent3 ?>
  </div>
  
<h3><?php echo $futabhead4 ?></h3>
  <div style="height:6em;min-height:6em;">  
 <?php echo $futabcontent4 ?>
  </div>
</div>
</div><!--end tabs_collapsed, desktop < 768px wide -->

      <div id="fu_libraryhours" class="col-md-4" style="background-color:#900028; padding:20px 10px; color:#fff; min-height:400px;">
         <?php echo $fu_hours_head ?>
          <?php echo $fuhourscontent ?> 
       </div>
  <div style="clear:both;"></div>

</div>
</div>



-------------------------------------------------------------------
FRONT-END JAVASCRIPT
-------------------------------------------------------------------
if (document.getElementById('tabs') ){

   $i(document).ready(function() {
   $i( "#tabs" ).tabs();
});
}






