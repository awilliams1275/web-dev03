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
 ?>

<style>
.img_src{width:100%;}

</style>

<section class="slider-numbers image" style="overflow:hidden;">
	<div class="w_row twelve-hun-max">
<?php $fu_ad_title_1 = "%FU_AD_TITLE_1%";
if ( strlen($fu_ad_title_1) == 0 ) {
$fu_adtitle1 = "";
}else{
$fu_adtitle1 = "<h5 class=\"section-title small-12 columns\">%FU_AD_TITLE_1%</h5>";
echo $fu_adtitle1;}
?>
		<div class="large-12 columns">
			<div class="w_row">
				<ul class="list-style-type:none;">
				  <li>
					<img src="http://<?php print DOMAIN ?>/images/%FU_AD_SRC1A%" alt="" title="" class="fu_ad_img" />
					<div class="orbit-caption fu_ad_div">
						<p class="fu_ad_larger">%FU_AD_HEAD%</p>
						<p class="fu_ad_smaller">%FU_AD_TEXT%</p>
						<p><a href="%FU_AD_TAG_URL%" class="button" title="">%FU_AD_TAG%</a></p>
					</div>
				  </li>			 
				</ul>
			</div>
		</div>
<?php $fu_ad_title_2 = "%FU_AD_TITLE_2%";
if ( strlen($fu_ad_title_2) == 0 ) {
$fu_adtitle2 = "";
}else{
$fu_adtitle2 = "<a class=\"section-link hide-for-small\" href=\"%FU_AD_TITLE_LINK%\" title=\"\">%FU_AD_TITLE_2%</a>";
echo $fu_adtitle_2;}
?>			
	</div>
</section>


--------------------------------------------------------
SETTINGS
--------------------------------------------------------
<table class="form_table" id="tbl_widget_content">
<input type="hidden" value="<?php print $DOMAIN; ?>" id="DOMAIN" />

<tr>
<td class="label_cell">Ad Space Title </td>
<td class="data_cell"><input id="fu_ad_title_1" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Call to Action Text</td>
<td class="data_cell"><input id="fu_ad_title_2" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Call to Action URL</td>
<td class="data_cell"><input id="fu_ad_title_link" value="" size="45" type="text" />
</td></tr>


<tr><td class="label_cell">Background Image*</td>
<td class="data_cell">
<input type="hidden" id="fu_ad_src1a" value="" onchange="$('fu_ad_srci1a').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="fu_ad_srci1a" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=fu_ad_srci1a&imageFilenameID=fu_ad_src1a'); " />
</td></tr>

<tr><td class="label_cell">Headline:*</td><td class="data_cell"><input id="fu_ad_head" value="" type="text"  size="35" /></td></tr>

<tr><td class="label_cell">Text:*</td><td class="data_cell"><input id="fu_ad_text" value="" type="text"  size="35" /></td></tr>

<tr><td class="label_cell">Tagline:</td><td class="data_cell"><input id="fu_ad_tag" value="" type="text"  size="35" /></td></tr>

<tr><td class="label_cell">Taagline URL:</td><td class="data_cell"><input id="fu_ad_tag_url" value="" type="text"  size="35" /></td></tr>

</table>

---------------------------------------------------------
SETTINGS JAVASCRIPT
---------------------------------------------------------
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
 
	
    var fu_ad_src1a = $('fu_ad_src1a').value; 
    if (fu_ad_src1a == '') {
        alert('Please enter a background image');
        return false;
    }
	
    var fu_ad_head = $('fu_ad_head').value; 
    if (fu_ad_head == '') {
        alert('Please enter a headline');
        return false;
    }
	var fu_ad_text = $('fu_ad_text').value; 
    if (fu_ad_text == '') {
        alert('Please enter descriptive text');
        return false;
    }

	
    widgetItems[activeWidget].settings = new Object();
    widgetItems[activeWidget].settings['fu_ad_title_1'] = fu_ad_title_1;
	widgetItems[activeWidget].settings['fu_ad_title_2'] = fu_ad_title_2;
	widgetItems[activeWidget].settings['fu_ad_title_link'] = fu_ad_title_link;
	widgetItems[activeWidget].settings['fu_ad_src1a'] = fu_ad_src1a;
    
	widgetItems[activeWidget].settings['fu_ad_text'] = fu_ad_text;
	widgetItems[activeWidget].settings['fu_ad_head'] = fu_ad_head;
	widgetItems[activeWidget].settings['fu_ad_tag'] = fu_ad_tag;
	widgetItems[activeWidget].settings['fu_ad_tag_url'] = fu_ad_tag_url;
	
    return true;
}

	

