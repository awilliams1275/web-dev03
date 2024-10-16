FRONT END:
-----------------------------------------------------------------------
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


//for some reason the light box strips out the "." in the image filename. Putting it back:
   function addp($theimg){
   $newimg = substr_replace($theimg, ".", strlen($theimg)-3);
   $newimg = $newimg .substr($theimg,-3);
   return $newimg;
   }
   
 ?>

<style>
.img_src{width:100%;height:50%;}

</style>

<section class="slider-numbers image" style="overflow:hidden;">
	<div class="w_row twelve-hun-max">
		<h5 class="section-title small-12 columns">%FU_FBTN_1%</h5>
		<div class="large-12 columns">
			<div class="w_row">
				<ul style="list-style-type:none;">
				  <li>
					
					<img src="http://<?php print DOMAIN ?>/images/<?php print addp(%FU_IMG_SRC1B%);?>" alt="" title="" class="img_src" style="position:relative;" />
					<div class="orbit-caption fu_fbtn_div" style="height:100%;">
						<p style="height:100%;"><img src="http://<?php print DOMAIN ?>/images/<?php print addp(%FU_IMG_SRC1A%);?>" alt="" title="" class="fu_fbtn_img" />
						<p class="fu_fbtn_text"><?php
$img_text1  = "%FU_IMG_TEXT1%";
print $img_text1;
?></p>
			</div>
				  </li>
				  			 
				</ul>
			</div>
		</div>
		<a class="section-link hide-for-small" href="%FU_FBTN_2%" title="" style="position:absolute;">%FU_FBTN_2_TEXT%</a>
	</div>
</section>

--------------------------------------------------------------------------
SETTINGS:
--------------------------------------------------------------------------
<table class="form_table" id="tbl_widget_content">
<input type="hidden" value="<?php print $DOMAIN; ?>" id="DOMAIN" />

<tr>
<td class="label_cell">Fordham by the Numbers Name*</td>
<td class="data_cell"><input id="fu_fbtn_1" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">View All Numbers Link</td>
<td class="data_cell"><input id="fu_fbtn_2" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">View All Numbers Link Text</td>
<td class="data_cell"><input id="fu_fbtn_2_text" value="" size="45" type="text" />
</td></tr>

<tr><td class="label_cell">Foreground Image 1*</td>
<td class="data_cell">
<input type="hidden" id="fu_img_src1a" value="" onchange="$('fu_img_srci1a').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="fu_img_srci1a" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=fu_img_srci1a&imageFilenameID=fu_img_src1a'); " />
</td></tr>
<tr><td class="label_cell">Background Image 1*</td>
<td class="data_cell">
<input type="hidden" id="fu_img_src1b" value="" onchange="$('fu_img_srci1b').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="fu_img_srci1b" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=fu_img_srci1b&imageFilenameID=fu_img_src1b'); " />
</td></tr>
<tr><td class="label_cell">Caption 1:*</td><td class="data_cell"><input id="fu_img_text1" value="" type="text"  size="35" maxlength="50" /></td></tr>
<tr><td colspan="2" class="label_cell" style="background-color:#ffffff;"> </td></tr>

</table>


-------------------------------------------------------------------------
SETTINGS  JAVASCRIPT:
-------------------------------------------------------------------------
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
 var fu_fbtn_1 = $('fu_fbtn_1').value; 
    if (fu_fbtn_1 == '') {
        alert('Please enter the Fordham by the Numbers Name');
        return false;
    }
	
	var fu_fbtn_2 = $('fu_fbtn_2').value; 
    if (fu_fbtn_2 == '') {
         $("fu_fbtn_2").value == '';
        return true;
    }

	var fu_fbtn_2_text = $('fu_fbtn_2_text').value; 
    if (fu_fbtn_2_text == '') {
        $("fu_fbtn_2_text").value == '';
        return true;
    }
	
    var fu_img_src1b = $('fu_img_src1b').value; 
    if (fu_img_src1b == '') {
        alert('Please enter a background image for slide 1');
        return false;
    }
	
    var fu_img_src1a = $('fu_img_src1a').value; 
    if (fu_img_src1a == '') {
        alert('Please enter a foreground image for slide 1');
        return false;
    }
	var fu_img_text1 = $('fu_img_text1').value; 
    if (fu_img_text1 == '') {
        alert('Please enter a caption for slide 1.');
        return false;
    }
	
	
    widgetItems[activeWidget].settings = new Object();
    widgetItems[activeWidget].settings['fu_fbtn_1'] = fu_fbtn_1;
	widgetItems[activeWidget].settings['fu_fbtn_2'] = fu_fbtn_2;
widgetItems[activeWidget].settings['fu_fbtn_2_text'] = fu_fbtn_2_text;
	
	widgetItems[activeWidget].settings['fu_img_src1a'] = fu_img_src1a;
	widgetItems[activeWidget].settings['fu_img_src1b'] = fu_img_src1b;
	widgetItems[activeWidget].settings['fu_img_text1'] = fu_img_text1;
	

    return true;
}

