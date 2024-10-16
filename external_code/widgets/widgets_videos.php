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
?>

<?php
$VIDEOURL_B_1  = "%VIDEOURL_B_1%";
$VIDEOIMG_B_1  = "%VIDEOIMG_B_1%";

$VIDEOURL_B_2  = "%VIDEOURL_B_2%";
$VIDEOIMG_B_2  = "%VIDEOIMG_B_2%";

$VIDEOURL_B_3  = "%VIDEOURL_B_3%";
$VIDEOIMG_B_3  = "%VIDEOIMG_B_3%";

$VIDEOURL_B_4  = "%VIDEOURL_B_4%";
$VIDEOIMG_B_4  = "%VIDEOIMG_B_4%";

$VIDEOURL_B_5  = "%VIDEOURL_B_5%";
$VIDEOIMG_B_5  = "%VIDEOIMG_B_5%";
?>

<section class="slider-numbers video image">
	<div class="row twelve-hun-max">
		<h5 class="section-title small-12 columns">Gabelli Videos</h5>
		<div class="large-12 columns">
			<div class="row">
				<ul data-orbit class="orbit">
				  <li>
					<img src="http://<?php print DOMAIN ?>/images/<?php print $VIDEOIMG_B_1 ?>" alt="" title="" />
					<div class="orbit-caption">
						<p><a href="#"  class="videoModal-1"><img src="/images/video_circle.png" alt="" title="" /><span><?php 
$VIDEOS_B_1  = "%VIDEOS_B_1%";
print $VIDEOS_B_1;
?></span></a></p>
					</div>
				  </li>
				  <li>
					<img src="http://<?php print DOMAIN ?>/images/<?php print $VIDEOIMG_B_2 ?>" title="" alt="" />
					<div class="orbit-caption">
						<p><a href="#"  class="videoModal-2"><img src="/images/video_circle.png" alt="" title="" /><span><?php
$VIDEOS_B_2  = "%VIDEOS_B_2%";
print $VIDEOS_B_2;
?></span></a></p>
					</div>
				  </li>
				  <li>
					<img src="http://<?php print DOMAIN ?>/images/<?php print $VIDEOIMG_B_3 ?>" alt="" title="" />
					<div class="orbit-caption">
						<p><a href="#"  class="videoModal-3"><img src="/images/video_circle.png" alt="" title="" /><span><?php
$VIDEOS_B_3  = "%VIDEOS_B_3%";
print $VIDEOS_B_3;
?></span></a></p>
					</div>
				  </li>
				  <li>
					<img src="http://<?php print DOMAIN ?>/images/<?php print $VIDEOIMG_B_4 ?>" alt="" title="" />
					<div class="orbit-caption">
						<p><a href="#"  class="videoModal-4"><img src="/images/video_circle.png" alt="" title="" /><span><?php
$VIDEOS_B_4  = "%VIDEOS_B_4%";
print $VIDEOS_B_4;
?></span></a></p>
					</div>
				  </li>
 <li>
					<img src="http://<?php print DOMAIN ?>/images/<?php print $VIDEOIMG_B_5 ?>" alt="" title="" />
					<div class="orbit-caption">
						<p><a href="#"  class="videoModal-5"><img src="/images/video_circle.png" alt="" title="" /><span><?php
$VIDEOS_B_5  = "%VIDEOS_B_5%";
print $VIDEOS_B_5;
?></span></a></p>
					</div>
				  </li>				  
				</ul>
			</div>
		</div>
		<a class="section-link" href="#" title="">View All Videos</a>	</div>
</section>

<div id="videoModal-1" class="reveal-modal large">
		<div class="modal-content">
			<h2>Video 1</h2>
			<div class="flex-video">
				<iframe width="800" height="315" src="<?php print $VIDEOURL_B_1 ?>" style="border:none;" allowfullscreen></iframe>
			</div>
		</div>
		<a class="close-reveal-modal">×</a>
  </div>
  
  	<div id="videoModal-2" class="reveal-modal large">
		<div class="modal-content">
			<h2>Video 2</h2>
			<div class="flex-video">
				<iframe width="800" height="315" src="<?php print $VIDEOURL_B_2 ?>" style="border:none;" allowfullscreen></iframe>
			</div>
		</div>
		<a class="close-reveal-modal">×</a>
  </div>
  
  	<div id="videoModal-3" class="reveal-modal large">
		<div class="modal-content">
			<h2>Video 3</h2>
			<div class="flex-video">
				<iframe width="800" height="315" src="<?php print $VIDEOURL_B_3 ?>" style="border:none;" allowfullscreen></iframe>
			</div>
		</div>
		<a class="close-reveal-modal">×</a>
  </div>
  
  	<div id="videoModal-4" class="reveal-modal large">
		<div class="modal-content">
			<h2>Video 4</h2>
			<div class="flex-video">
				<iframe width="800" height="315" src="<?php print $VIDEOURL_B_4 ?>" style="border:none;" allowfullscreen></iframe>
			</div>
		</div>
		<a class="close-reveal-modal">×</a>
  </div>
  
  <div id="videoModal-5" class="reveal-modal large">
		<div class="modal-content">
			<h2>Video 5</h2>
			<div class="flex-video">
				<iframe width="800" height="315" src="<?php print $VIDEOURL_B_5 ?>" style="border:none;" allowfullscreen></iframe>
			</div>
		</div>
		<a class="close-reveal-modal">×</a>
  </div>
  



--------------------------------------------------------------
SETTINGS
--------------------------------------------------------------
<table class="form_table" id="tbl_widget_content">
<input type="hidden" value="<?php print $DOMAIN; ?>" id="DOMAIN" />
<tr><td class="label_cell">Left Head</td>
<td class="data_cell"><input id="left_head" value="" size="45" type="text" />
</td>
</tr>

<tr>
<td class="label_cell">Right Head Link</td>
<td class="data_cell"><input id="right_head" value="" size="45" type="text" />
</td>
</tr>

<tr><td colspan="2" bgcolor="#ffffff">
 
</td></tr>


<tr><td class="label_cell">Video text 1</td><td class="data_cell"><input id="videos_b_1" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">URL:</td><td class="data_cell"><input id="videourl_b_1" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">Image:</td><td class="data_cell"><input type="hidden" id="videoimg_b_1" value="" onchange="$('videoimgi_b_1').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="videoimgi_b_1" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=videoimgi_b_1&imageFilenameID=videoimg_b_1'); " /></td></tr>
<tr><td colspan="2" bgcolor="#ffffff"></td></tr>

<tr><td class="label_cell">Video text 2</td><td class="data_cell"><input id="videos_b_2" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">URL:</td><td class="data_cell"><input id="videourl_b_2" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">Image:</td><td class="data_cell"><input type="hidden" id="videoimg_b_2" value="" onchange="$('videoimgi_b_2').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="videoimgi_b_2" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=videoimgi_b_2&imageFilenameID=videoimg_b_2'); " /></td></tr>
<tr><td colspan="2" bgcolor="#ffffff"></td></tr>

<tr><td class="label_cell">Video text 3</td><td class="data_cell"><input id="videos_b_3" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">URL:</td><td class="data_cell"><input id="videourl_b_3" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">Image:</td><td class="data_cell"><input type="hidden" id="videoimg_b_3" value="" onchange="$('videoimgi_b_3').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="videoimgi_b_3" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=videoimgi_b_3&imageFilenameID=videoimg_b_3'); " /></td></tr>
<tr><td colspan="2" bgcolor="#ffffff"></td></tr>

<tr><td class="label_cell">Video text 4</td><td class="data_cell"><input id="videos_b_4" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">URL:</td><td class="data_cell"><input id="videourl_b_4" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">Image:</td><td class="data_cell"><input type="hidden" id="videoimg_b_4" value="" onchange="$('videoimgi_b_4').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="videoimgi_b_4" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=videoimgi_b_4&imageFilenameID=videoimg_b_4'); " /></td></tr>
<tr><td colspan="2" bgcolor="#ffffff"></td></tr>

<tr><td class="label_cell">Video text 5</td><td class="data_cell"><input id="videos_b_5" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">URL:</td><td class="data_cell"><input id="videourl_b_5" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">Image:</td><td class="data_cell"><input type="hidden" id="videoimg_b_5" value="" onchange="$('videoimgi_b_5').src='http://' + DOMAIN + '/images/' + this.value;"  />
<img id="videoimgi_b_5" class="img_preview" src="<?php print $SECURE_JADU_PATH; ?>/images/no_image.gif" />
<input type="button" class="button" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb2', 'mode=lb2&imagePreviewID=videoimgi_b_5&imageFilenameID=videoimg_b_5'); " /></td></tr>
</table>


