FRONT-END
-----------------------------------------------------------------
<div id="fu_container">
<div id="jumbotron" style="background-image:url('http://www.library.fordham.edu/images/bg4.jpg');">
<link rel="stylesheet" href="/site/custom_css/jquery-ui.min.css">
<link rel="stylesheet" href="/site/custom_css/jquery-ui.theme.min.css">
  <script src="//code.jquery.com/jquery-1.12.4.js"></script>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="http://www.library.fordham.edu/css/sirsiform.css">
<?php 
//include_once('websections/JaduHomepageWidgetSettings.php');
include_once('custom/mobile_detect.php');
$detect = new Mobile_Detect;

$futabhead1 = "%FU_TAB_HEAD_1%";
$futabcontent1 = "%FU_TAB_CONTENT_1%";

$futabhead2 = "%FU_TAB_HEAD_2%";
$futabcontent2 = ("%FU_TAB_CONTENT_2%");

if ( $futabhead2 ){
$futabhead2 = '<li><a href="#tabs-2">'.$futabhead2.'</a></li>';
$futabcontent2 =  '<div id="tabs-2">'.$futabcontent2.'</div>';
}else{
  $futabhead2 = "";
  $futabcontent2 = "";
}

$futabhead3 = "%FU_TAB_HEAD_3%";
$futabcontent3 = "%FU_TAB_CONTENT_3%";

if ( $futabhead3 ){
$futabhead3 = '<li><a href="#tabs-3">'.$futabhead3.'</a></li>';
$futabcontent3 = '<div id="tabs-3">'.$futabcontent3.'</div>';
}else{
  $futabhead3 = "";
  $futabcontent3 = "";
}

$futabhead4 = "%FU_TAB_HEAD_4%";
$futabcontent4 = "%FU_TAB_CONTENT_4%";

if ( $futabhead4 ){
$futabhead4 = '<li><a href="#tabs-4">'.$futabhead4.'</a></li>';
$futabcontent4 = '<div id="tabs-4">'.$futabcontent4.'</div>';
}else{
  $futabhead4 = "";
  $futabcontent4 = "";
}

$futabhead5 = "%FU_TAB_HEAD_5%";
$futabcontent5 = "%FU_TAB_CONTENT_5%";

if ( $futabhead5 ){
$futabhead5 = '<li><a href="#tabs-5">'.$futabhead5.'</a></li>';
$futabcontent5 = '<div id="tabs-5">'.$futabcontent5.'</div>';
}else{
  $futabhead5 = "";
  $futabcontent5 = "";
}

$fuhourscontent = '%FU_HOURS_CONTENT%';

if ( $detect->isMobile()   || $detect->isTablet()  ||$detect->isiOS() || $detect->isAndroidOS()  ) {
?>
<!--for mobile -->
 <script>
var $i = jQuery.noConflict();
 $i(function() {
    $i( "#accordion" ).accordion();
  });
 </script> 

<div id="accordion">
<h3><?php echo $futabhead1 ?></h3>
  <div  style="max-height:10em;">
    <p><?php echo $futabcontent1 ?></p>
  </div>
  
<h3><?php echo $futabhead2 ?></h3>
  <div>
    <p><?php echo $futabcontent2 ?></p>
 </div>
  
<h3><?php echo $futabhead3 ?></h3>
  <div style="max-height:12em;">
    <p> <?php echo $futabcontent3 ?></p>
  </div>
  
<h3><?php echo $futabhead4 ?></h3>
  <div id="futab4">  
    <?php echo $futabcontent4?>
  </div>
</div>
</div><!--end tabs_collapsed, desktop < 768px wide -->

<?php
}
else{

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
    $i( "#accordion" ).accordion();
  });
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
 <?php echo $futabcontent3 ?>
  </div>
  
<h3><?php echo $futabhead4 ?></h3>
  <div style="height:6em;min-height:6em;">  
 <?php echo $futabcontent4?>
  </div>
</div>
</div><!--end tabs_collapsed, desktop < 768px wide -->
<?php
}
?> 
      <div id="fu_libraryhours" class="col-md-4" style="background-color:#900028; padding:20px 10px; color:#fff; min-height:400px;">
          <?php echo $fuhourscontent ?> 
       </div>
  <div style="clear:both;"></div>

</div>
</div>



----------------------------------------------------------------------
FRONT-END JAVASCRIPT
----------------------------------------------------------------------
if (document.getElementById('tabs') ){

   $i(document).ready(function() {
   $i( "#tabs" ).tabs();
});
}

springshare_widget_config_1441984503194 = {  };
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://lgapi.libapps.com/widgets.php?site_id=513&widget_type=7&widget_embed_type=1&output_format=1&search_box_type=2&placeholder_text=Enter+Search+Words&button_text=Search&widget_height=&widget_width=250&config_id=1441984503194";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","s-lg-widget-script-1441984503194");


----------------------------------------------------------------------
SETTINGS
----------------------------------------------------------------------
<table class="form_table" id="tbl_widget_content">
<tr><td class="data_cell" colspan="2">Tab 1</td></tr>
<tr>
<td class="label_cell">Tab 1 Title*</td>
<td class="data_cell"><input id="fu_tab_head_1" value="" size="45" type="text" />
</td></tr>
<tr>
<td class="label_cell">Tab 1 Content*</td>
<td class="data_cell"><textarea id="fu_tab_content_1" value="" rows="6" cols="4"></textarea>
</td></tr>

<tr><td class="data_cell" colspan="2">Tab 2</td></tr>
<tr>
<td class="label_cell">Tab 2 Title</td>
<td class="data_cell"><input id="fu_tab_head_2" value="" size="45" type="text" />
</td></tr>
<tr>
<td class="label_cell">Tab 2 Content</td>
<td class="data_cell"><textarea id="fu_tab_content_2" value="" rows="6" cols="4"></textarea>
</td></tr>

<tr><td class="data_cell" colspan="2">Tab 3</td></tr>
<tr>
<td class="label_cell">Tab 3 Title</td>
<td class="data_cell"><input id="fu_tab_head_3" value="" size="45" type="text" />
</td></tr>
<tr>
<td class="label_cell">Tab 3 Content</td>
<td class="data_cell"><textarea id="fu_tab_content_3" value="" rows="6" cols="4"></textarea>
</td></tr>

<tr><td class="data_cell" colspan="2">Tab 4</td></tr>
<tr>
<td class="label_cell">Tab 4 Title</td>
<td class="data_cell"><input id="fu_tab_head_4" value="" size="45" type="text" />
</td></tr>
<tr>
<td class="label_cell">Tab 4 Content</td>
<td class="data_cell"><textarea id="fu_tab_content_4" value="" rows="6" cols="4"></textarea>
</td></tr>

<tr><td class="data_cell" colspan="2">Tab 5</td></tr>
<tr>
<td class="label_cell">Tab 5 Title</td>
<td class="data_cell"><input id="fu_tab_head_5" value="" size="45" type="text" />
</td></tr>
<tr>
<td class="label_cell">Tab 5 Content</td>
<td class="data_cell"><textarea id="fu_tab_content_5" value="" rows="6" cols="4"></textarea>
</td></tr>


<tr><td class="data_cell" colspan="2">Hours Box</td></tr>
<tr>
<td class="label_cell">Hours This Week Content</td>
<td class="data_cell"><textarea id="fu_hours_content" value="" rows="6" cols="4"></textarea>
</td></tr>
</table>





----------------------------------------------------------------------
SETTINGS JAVASCRIPT
----------------------------------------------------------------------

var currentLinkEdit = -1;
var widgetLinks = new Array();
var oldsave = $('saveWidgetProperty').onclick;

if (typeof $('saveWidgetProperty').onclick != 'function') {
    $('saveWidgetProperty').onclick = commitWidgetLinks;
}
else {
    $('saveWidgetProperty').onclick = function () {
        if (commitWidgetLinks()) {
            oldsave();
        }
    }
}

function commitWidgetLinks ()
{
 var fu_tab_head_1 = $('fu_tab_head_1').value; 
    if (fu_tab_head_1 == '') {
        alert('Please enter the Tab 1 Head');
        return false;
}
  
var fu_tab_content_1 = $('fu_tab_content_1').value; 
    if (fu_tab_content_1 == '') {
         alert('Please enter the Tab 1 Content');
        return false;
}

 var fu_tab_content_3 = $('fu_tab_content_3').value; 
  
    widgetItems[activeWidget].settings = new Object();
    widgetItems[activeWidget].settings['fu_tab_head_1'] = (fu_tab_head_1);
  widgetItems[activeWidget].settings['fu_tab_content_1'] = fu_tab_content_1;


  

    return true;
}

