

FRONT-END
---------------------------------------------------------------------------
<link rel="stylesheet" href="/site/custom_css/jquery-ui.css">
<link rel="stylesheet" href="/site/custom_css/jquery-ui.structure.min.css">
<link rel="stylesheet" href="/site/custom_css/jquery-ui.theme.min.css">

<script src="/site/javascript/jquery.min.js"></script>
<script>
    var $i = jQuery.noConflict();
</script> 

<?php	
include_once('websections/JaduHomepageWidgetSettings.php');

$futabhead1 = "%FU_TAB_HEAD_1%";
$futabcontent1 = "%FU_TAB_CONTENT_1%";


$futabhead2 = "%FU_TAB_HEAD_2%";
$futabcontent2 = "%FU_TAB_CONTENT_2%";

if ( $futabhead2 ){
$futabhead2 = '<li><a href="#tabs-2">%FU_TAB_HEAD_2%</a></li>';
$futabcontent2 = '<div id="tabs-2">%FU_TAB_CONTENT_2%</div>';
}else{
	$futabhead2 = "";
	$futabcontent2 = "";
}

$futabhead3 = "%FU_TAB_HEAD_3%";
$futabcontent3 = "%FU_TAB_CONTENT_3%";

if ( $futabhead3 ){
$futabhead3 = '<li><a href="#tabs-3">%FU_TAB_HEAD_3%</a></li>';
$futabcontent3 = '<div id="tabs-3">%FU_TAB_CONTENT_3%</div>';
}else{
	$futabhead3 = "";
	$futabcontent3 = "";
}

$futabhead4 = "%FU_TAB_HEAD_4%";
$futabcontent4 = "%FU_TAB_CONTENT_4%";

if ( $futabhead4 ){
$futabhead4 = '<li><a href="#tabs-4">%FU_TAB_HEAD_4%</a></li>';
$futabcontent4 = '<div id="tabs-4">%FU_TAB_CONTENT_4%</div>';
}else{
	$futabhead4 = "";
	$futabcontent4 = "";
}

$futabhead5 = "%FU_TAB_HEAD_5%";
$futabcontent5 = "%FU_TAB_CONTENT_5%";

if ( $futabhead5 ){
$futabhead5 = '<li><a href="#tabs-5">%FU_TAB_HEAD_5%</a></li>';
$futabcontent5 = '<div id="tabs-5">%FU_TAB_CONTENT_5%</div>';
}else{
	$futabhead5 = "";
	$futabcontent5 = "";
}

$fuhourscontent = '%FU_HOURS_CONTENT%';
?>


<div id="container">
<div id="jumbotron" style="background-image:url('http://www.library.fordham.edu/images/bg4.jpg');">


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
			</div><!--col-md-8-->


        </div>

      <div class="col-md-4" style="background-color:#900028; padding:20px 10px; color:#fff; min-height:400px;">
          <?php echo $fuhourscontent ?> 
       </div>
  <div style="clear:both;"></div>
</div><!--jumbotron-->
</div><!--container-->


</div>

  <script src="/site/javascript/jquery-ui.min.js"></script>

	
	--------------------------------------------------------------
FRONT-END JAVASCRIPT
-------------------------------------------------------------------

if (document.getElementById('tabs') ){

   $i(document).ready(function() {
   $i( "#tabs" ).tabs();
});

}


	--------------------------------------------------------------
SETTINGS 
-------------------------------------------------------------------
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

	--------------------------------------------------------------
SETTINGS JAVASCRIPT
-------------------------------------------------------------------
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

 
	
    widgetItems[activeWidget].settings = new Object();
    widgetItems[activeWidget].settings['fu_tab_head_1'] = fu_tab_head_1;
	widgetItems[activeWidget].settings['fu_tab_content_1'] = fu_tab_content_1;

	

    return true;
}