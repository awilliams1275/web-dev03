--------------------------------------------------------------
FRONT-END
--------------------------------------------------------------
<?php 
include_once('websections/JaduHomepageWidgetSettings.php');
include_once('custom/signpost/CustomSignpostWidgetSettings.php');

// fetch all widget settings to later determine cookie list items
if (isset($_POST['preview'])) {
  $newSettings = array();

  $j = 0;
  
  if (!empty($settings)) {
    foreach ($settings as $name => $value) {
      $newSettings[$j] = new stdClass();
      $newSettings[$j]->name = $name;
      $newSettings[$j]->value = $value;
      
      $newSettings[$j]->value= str_replace('_apos_', "'", $newSettings[$j]->value);
      $newSettings[$j]->value = str_replace('_amp_', "&", $newSettings[$j]->value);
      $newSettings[$j]->value = str_replace('_eq_', '=', $newSettings[$j]->value);
      $newSettings[$j]->value = str_replace('_hash_', '#', $newSettings[$j]->value);
      $newSettings[$j]->value = str_replace('_ques_', '?', $newSettings[$j]->value);
      $newSettings[$j]->value = str_replace('_perc_', '%', $newSettings[$j]->value);
      $newSetting->value = stripslashes($newSetting->value);
      
      $j++;
    }
  }
  
  $settings = $newSettings;   
}
else {
  if (isset($widget) && !is_array($widget)) {
    if (isset($_POST['homepageContent'])) {
      $settings = array();
      foreach ($widgetSettings[$widget->id] as $setting) {
        $newSetting = new WidgetSetting();
        $newSetting->name = $setting->name;
        $newSetting->value = $setting->value;
        
        $newSetting->value= str_replace('_apos_', "'", $newSetting->value);
        $newSetting->value = str_replace('_amp_', "&", $newSetting->value);
        $newSetting->value = str_replace('_eq_', '=', $newSetting->value);
        $newSetting->value = str_replace('_hash_', '#', $newSetting->value);
        $newSetting->value = str_replace('_ques_', '?', $newSetting->value);
        $newSetting->value = str_replace('_perc_', '%', $newSetting->value);
        
        $settings[] = $newSetting;
      }
    }
    else if (isset($_POST['action']) && $_POST['action'] == 'getPreviews') {
      $settings = getAllSettingsForHomepageWidget($aWidget->id);
    }
    else if(isset($signpost) && $signpost->id > 0) {
      $settings = getAllSignpostWidgetSettingsBySignpostID($signpost->id);
    }
    else {
      $settings = getAllSettingsForHomepageWidget($widget->id, true);
    }
  }
  else {
    if (isset($_POST['homepageContent'])) {
      $settings = array();
      foreach ($widgetSettings[$stack->id] as $setting) {
        $newSetting = new WidgetSetting();
        $newSetting->name = $setting->name;
        $newSetting->value = $setting->value;
        $settings[] = $newSetting;
      }
    }
    else if (isset($_POST['getPreviews'])) {
      $settings = getAllSettingsForHomepageWidget($aWidget->id);
    }
    else {
      $settings = getAllSettingsForHomepageWidget($stack->id, true);
    }
  }
}

$fu_hp_fbtn_1 = "%FU_HP_FBTN_1%";

if ( strlen($fu_hp_fbtn_1) == 0 ){
$fu_hp_fbtn_1 = "";}
else{
$fu_hp_fbtn_1 = "<h5 class='section-title small-12 columns'>%FU_HP_FBTN_1%</h5>";
}
?>

<section class="programs">
    <div class="w_row twelve-hun-max">
	
	<?php print $fu_hp_fbtn_1; ?>

<figure class="small-12 large-20p column red">
<?php 
$fu_hp_link_1 = "%FU_HP_LINK_1%";
if ( strlen($fu_hp_link_1) == 0 ){
$fulink1_a1 = "";
$fulink1_a2 = "";
}else{
$fulink1_a1 = "<a href=%FU_HP_LINK_1%>";
$fulink1_a2 = "</a>";
}
?>
<?php echo $fulink1_a1; ?>
<img src="//<?php print DOMAIN ?>/images/%FU_HP_IMG_1%" title="%FU_HP_TITLE_1%" alt="%FU_HP_TITLE_1%" />
<figcaption>
<div class="title">%FU_HP_TITLE_1%</div>
<p>%FU_HP_DESC_1%</p>
</figcaption>
<?php echo $fulink1_a2; ?>
</figure>

<figure class="small-12 large-20p column red">
<?php 
$fu_hp_link_2 = "%FU_HP_LINK_2%";
if ( strlen($fu_hp_link_2) == 0 ){
$fulink2_a1 = "";
$fulink2_a2 = "";
}else{
$fulink2_a1 = "<a href=%FU_HP_LINK_2%>";
$fulink2_a2 = "</a>";
}
?>
<?php echo $fulink2_a1; ?>
<img src="//<?php print DOMAIN ?>/images/%FU_HP_IMG_2%" title="%FU_HP_TITLE_2%" alt="%FU_HP_TITLE_2%" />
<figcaption>
<div class="title">%FU_HP_TITLE_2%</div>
<p>%FU_HP_DESC_2%</p>
</figcaption>
<?php echo $fulink2_a2; ?>
</figure>

<figure class="small-12 large-20p column red">
<?php 
$fu_hp_link_3 = "%FU_HP_LINK_3%";
if ( strlen($fu_hp_link_3) == 0 ){
$fulink3_a1 = "";
$fulink3_a2 = "";
}else{
$fulink3_a1 = "<a href=%FU_HP_LINK_3%>";
$fulink3_a2 = "</a>";
}
?>
<?php echo $fulink3_a1; ?>
<img src="//<?php print DOMAIN ?>/images/%FU_HP_IMG_3%" title="%FU_HP_TITLE_3%" alt="%FU_HP_TITLE_3%" />
<figcaption>
<div class="title">%FU_HP_TITLE_3%</div>
<p>%FU_HP_DESC_3%</p>
</figcaption>
<?php echo $fulink3_a2; ?>
</figure>

<figure class="small-12 large-20p column red">
<?php 
$fu_hp_link_4 = "%FU_HP_LINK_4%";
if ( strlen($fu_hp_link_4) == 0 ){
$fulink4_a1 = "";
$fulink4_a2 = "";
}else{
$fulink4_a1 = "<a href=%FU_HP_LINK_4%>";
$fulink4_a2 = "</a>";
}
?>
<?php echo $fulink4_a1; ?>
<img src="//<?php print DOMAIN ?>/images/%FU_HP_IMG_4%" title="%FU_HP_TITLE_4%" alt="%FU_HP_TITLE_4%" />
<figcaption>
<div class="title">%FU_HP_TITLE_4%</div>
<p>%FU_HP_DESC_4%</p>
</figcaption>
<?php echo $fulink4_a2; ?>
</figure>

<figure class="small-12 large-20p column red">
<?php 
$fu_hp_link_5 = "%FU_HP_LINK_5%";
if ( strlen($fu_hp_link_5) == 0 ){
$fulink5_a1 = "";
$fulink5_a2 = "";
}else{
$fulink5_a1 = "<a href=%FU_HP_LINK_5%>";
$fulink5_a2 = "</a>";
}
?>
<?php echo $fulink5_a1; ?>
<img src="//<?php print DOMAIN ?>/images/%FU_HP_IMG_5%" title="%FU_HP_TITLE_5%" alt="%FU_HP_TITLE_5%" />
<figcaption>
<div class="title">%FU_HP_TITLE_5%</div>
<p>%FU_HP_DESC_5%</p>
</figcaption>
<?php echo $fulink5_a2; ?>
</figure>

<?php 
$fu_hp_fbtn_2 = "%FU_HP_FBTN_2%";
if ( strlen($fu_hp_fbtn_2) == 0 ) {
echo "";
}else{
echo "<a class=\"section-link\" href=\"%FU_HP_FBTN_2%\">VIEW ALL PROGRAMS</a>";}
?>
    </div>
  </section>
  
  
  
  

--------------------------------------------------------------
SETTINGS
--------------------------------------------------------------
<table class="form_table" id="tbl_widget_content">
<input type="hidden" value="<?php print $DOMAIN; ?>" id="DOMAIN" />
<tr>
<td class="label_cell">Highlighted Program Title</td>
<td class="data_cell"><input id="fu_hp_fbtn_1" value="" size="45" type="text" />
</td>
</tr>

<tr>
<td class="label_cell">View All Programs Link</td>
<td class="data_cell"><input id="fu_hp_fbtn_2" value="" size="45" type="text" />
</td>
</tr>

<tr><td  class="data_cell" colspan="2" bgcolor="#ffffff">Highlighted Program 1</td></tr>

<tr><td class="label_cell">Image 1:*</td><td class="data_cell"><input type="hidden" id="fu_hp_img_1" value="" onchange="$('fu_hp_imgi_1').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="fu_hp_imgi_1" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=fu_hp_imgi_1&imageFilenameID=fu_hp_img_1'); " /></td></tr>
<tr><td colspan="2" bgcolor="#ffffff"></td></tr>
<tr><td class="label_cell">Title 1*</td><td class="data_cell"><input id="fu_hp_title_1" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">Description 1:*</td><td class="data_cell"><input id="fu_hp_desc_1" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">URL 1:</td><td class="data_cell"><input id="fu_hp_link_1" value="" size="45" type="text" /></td></tr>

<tr><td  class="data_cell" colspan="2" bgcolor="#ffffff">Highlighted Program 2</td></tr>

<tr><td class="label_cell">Image 2:*</td><td class="data_cell"><input type="hidden" id="fu_hp_img_2" value="" onchange="$('fu_hp_imgi_2').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="fu_hp_imgi_2" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=fu_hp_imgi_2&imageFilenameID=fu_hp_img_2'); " /></td></tr>
<tr><td colspan="2" bgcolor="#ffffff"></td></tr>
<tr><td class="label_cell">Title 2:*</td><td class="data_cell"><input id="fu_hp_title_2" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">Description 2:*</td><td class="data_cell"><input id="fu_hp_desc_2" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">URL 2:</td><td class="data_cell"><input id="fu_hp_link_2" value="" size="45" type="text" /></td></tr>

<tr><td  class="data_cell" colspan="2" bgcolor="#ffffff">Highlighted Program 3</td></tr>

<tr><td class="label_cell">Image 3:*</td><td class="data_cell"><input type="hidden" id="fu_hp_img_3" value="" onchange="$('fu_hp_imgi_3').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="fu_hp_imgi_3" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=fu_hp_imgi_3&imageFilenameID=fu_hp_img_3'); " /></td></tr>
<tr><td colspan="2" bgcolor="#ffffff"></td></tr>
<tr><td class="label_cell">Title 3:*</td><td class="data_cell"><input id="fu_hp_title_3" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">Description 3:*</td><td class="data_cell"><input id="fu_hp_desc_3" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">URL 3:</td><td class="data_cell"><input id="fu_hp_link_3" value="" size="45" type="text" /></td></tr>

<tr><td  class="data_cell" colspan="2" bgcolor="#ffffff">Highlighted Program 4</td></tr>

<tr><td class="label_cell">Image 4:*</td><td class="data_cell"><input type="hidden" id="fu_hp_img_4" value="" onchange="$('fu_hp_imgi_4').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="fu_hp_imgi_4" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=fu_hp_imgi_4&imageFilenameID=fu_hp_img_4'); " /></td></tr>
<tr><td colspan="2" bgcolor="#ffffff"></td></tr>
<tr><td class="label_cell">Title 4:*</td><td class="data_cell"><input id="fu_hp_title_4" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">Description 4:*</td><td class="data_cell"><input id="fu_hp_desc_4" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">URL 4:</td><td class="data_cell"><input id="fu_hp_link_4" value="" size="45" type="text" /></td></tr>

<tr><td  class="data_cell" colspan="2" bgcolor="#ffffff">Highlighted Program 5</td></tr>

<tr><td class="label_cell">Image 5:*</td><td class="data_cell"><input type="hidden" id="fu_hp_img_5" value="" onchange="$('fu_hp_imgi_5').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="fu_hp_imgi_5" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=fu_hp_imgi_5&imageFilenameID=fu_hp_img_5'); " /></td></tr>
<tr><td colspan="2" bgcolor="#ffffff"></td></tr>
<tr><td class="label_cell">Title 5:*</td><td class="data_cell"><input id="fu_hp_title_5" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">Description 5:*</td><td class="data_cell"><input id="fu_hp_desc_5" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">URL 5:</td><td class="data_cell"><input id="fu_hp_link_5" value="" size="45" type="text" /></td></tr>
</table>



--------------------------------------------------------------
SETTINGS JAVASCRIPT
--------------------------------------------------------------
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

if (typeof widgetItems[activeWidget].settings['feature_image'] != 'undefined' && widgetItems[activeWidget].settings['feature_image'] != '') {
    $('img_srci').src = PROTOCOL + DOMAIN + '/images/' + widgetItems[activeWidget].settings['feature_image'];
}

function commitWidgetLinks ()
{
 var fu_fp_fbtn_1 = $('fu_hp_fbtn_1').value; 
    if (fu_hp_fbtn_1 == '') {
        alert('Please enter the Highlighted Program Name');
        return false;
    }
	
	var fu_hp_fbtn_2 = $('fu_hp_fbtn_2').value; 
    if (fu_hp_fbtn_2 == '') {
        alert('Please enter the View All Program link URL');
        return false;
    }
	
    var fu_hp_link_1 = $('fu_hp_link_1').value; 
    if (fu_hp_link_1 == '') {
        alert('Please enter a link URL for Program 1.');
        return false;
    }
	var fu_hp_img_1 = $('fu_hp_img_1').value; 
    if (fu_hp_img_1 == '') {
        alert('Please enter an image for Program 1.');
        return false;
    }
	var fu_hp_title_1 = $('fu_hp_title_1').value; 
    if (fu_hp_title_1 == '') {
        alert('Please enter a title for Program 1.');
        return false;
    }
	var fu_hp_desc_1 = $('fu_hp_desc_1').value; 
    if (fu_hp_desc_1 == '') {
        alert('Please enter a description for Program 1.');
        return false;
    }
	
    var fu_hp_link_2 = $('fu_hp_link_2').value; 
    if (fu_hp_link_2 == '') {
        alert('Please enter a link URL for Program 2.');
        return false;
    }
	var fu_hp_img_2 = $('fu_hp_img_2').value; 
    if (fu_hp_img_2 == '') {
        alert('Please enter an image for Program 2.');
        return false;
    }
	var fu_hp_title_2 = $('fu_hp_title_2').value; 
    if (fu_hp_title_2 == '') {
        alert('Please enter a title for Program 2.');
        return false;
    }
	var fu_hp_desc_2 = $('fu_hp_desc_2').value; 
    if (fu_hp_desc_2 == '') {
        alert('Please enter a description for Program 2.');
        return false;
    }
	
    var fu_hp_link_3 = $('fu_hp_link_3').value; 
    if (fu_hp_link_3 == '') {
        alert('Please enter a link URL for Program 3.');
        return false;
    }
	var fu_hp_img_3 = $('fu_hp_img_3').value; 
    if (fu_hp_img_3 == '') {
        alert('Please enter an image for Program 3.');
        return false;
    }
	var fu_hp_title_3 = $('fu_hp_title_3').value; 
    if (fu_hp_title_3 == '') {
        alert('Please enter a title for Program 3.');
        return false;
    }
	var fu_hp_desc_3 = $('fu_hp_desc_3').value; 
    if (fu_hp_desc_3 == '') {
        alert('Please enter a description for Program 3.');
        return false;
    }
	
    var fu_hp_link_4 = $('fu_hp_link_4').value; 
    if (fu_hp_link_4 == '') {
        alert('Please enter a link URL for Program 4.');
        return false;
    }
	var fu_hp_img_4 = $('fu_hp_img_4').value; 
    if (fu_hp_img_4 == '') {
        alert('Please enter an image for Program 4.');
        return false;
    }
	var fu_hp_title_4 = $('fu_hp_title_4').value; 
    if (fu_hp_title_4 == '') {
        alert('Please enter a title for Program 4.');
        return false;
    }
	var fu_hp_desc_4 = $('fu_hp_desc_4').value; 
    if (fu_hp_desc_4 == '') {
        alert('Please enter a description for Program 4.');
        return false;
    }
	
    var fu_hp_link_4 = $('fu_hp_link_4').value; 
    if (fu_hp_link_4 == '') {
        alert('Please enter a link URL for Program 4.');
        return false;
    }
	var fu_hp_img_4 = $('fu_hp_img_4').value; 
    if (fu_hp_img_4 == '') {
        alert('Please enter an image for Program 4.');
        return false;
    }
	var fu_hp_title_4 = $('fu_hp_title_4').value; 
    if (fu_hp_title_4 == '') {
        alert('Please enter a title for Program 4.');
        return false;
    }
	var fu_hp_desc_4 = $('fu_hp_desc_4').value; 
    if (fu_hp_desc_4 == '') {
        alert('Please enter a description for Program 4.');
        return false;
    }
  
    widgetItems[activeWidget].settings = new Object();
    widgetItems[activeWidget].settings['fu_hp_fbtn_1'] = fu_hp_fbtn_1;
    widgetItems[activeWidget].settings['fu_hp_fbtn_2'] = fu_hp_fbtn_2;

    widgetItems[activeWidget].settings['fu_hp_img_1'] = fu_hp_img_1;
	widgetItems[activeWidget].settings['fu_hp_title_1'] = fu_hp_title_1;
	widgetItems[activeWidget].settings['fu_hp_desc_1'] = fu_hp_desc_1;
	widgetItems[activeWidget].settings['fu_hp_link_1'] = fu_hp_link_1;
		
	widgetItems[activeWidget].settings['fu_hp_img_2'] = fu_hp_img_2;
	widgetItems[activeWidget].settings['fu_hp_title_2'] = fu_hp_title_2;
	widgetItems[activeWidget].settings['fu_hp_desc_2'] = fu_hp_desc_2;
	widgetItems[activeWidget].settings['fu_hp_link_2'] = fu_hp_link_2;
	
	widgetItems[activeWidget].settings['fu_hp_img_3'] = fu_hp_img_3;
	widgetItems[activeWidget].settings['fu_hp_title_3'] = fu_hp_title_3;
	widgetItems[activeWidget].settings['fu_hp_desc_3'] = fu_hp_desc_3;
	widgetItems[activeWidget].settings['fu_hp_link_3'] = fu_hp_link_3;
	
	widgetItems[activeWidget].settings['fu_hp_img_4'] = fu_hp_img_4;
	widgetItems[activeWidget].settings['fu_hp_title_4'] = fu_hp_title_4;
	widgetItems[activeWidget].settings['fu_hp_desc_4'] = fu_hp_desc_4;
	widgetItems[activeWidget].settings['fu_hp_link_4'] = fu_hp_link_4;
	
	widgetItems[activeWidget].settings['fu_hp_img_5'] = fu_hp_img_5;
	widgetItems[activeWidget].settings['fu_hp_title_5'] = fu_hp_title_5;
	widgetItems[activeWidget].settings['fu_hp_desc_5'] = fu_hp_desc_5;
	widgetItems[activeWidget].settings['fu_hp_link_5'] = fu_hp_link_5;
   
    return true;
}
	