FRONT-END
------------------------------------------------------------
lazy YT:
<?php

$VIDEOURL_B_1  = "%VIDEOURL_B_1%";
$VIDEOIMG_B_1  = "%VIDEOIMG_B_1%";
$LEFT_HEAD  = "%LEFT_HEAD%";
$RIGHT_HEAD  = "%RIGHT_HEAD%";

//get the id number of the vieo URL to use in the lazyYT div tag
$str = "http://www.youtube.com/embed/IkOQw96cfyE";
$out = array();
$arr = explode('/', $str);

$result = count($arr);
$videourl =  $arr[$result-1];

?>
<style>

.lazyYT-title {
    z-index: 100!important;
    color: #fff!important;
    font-family: sans-serif!important;
    font-size: 12px!important;
    top: 10px!important;
    left: 12px!important;
    position: absolute!important;
    margin: 0!important;
    padding: 0!important;
    line-height: 1!important;
    font-style: normal!important;
    font-weight: normal!important;
}

.lazyYT-button {
    margin: 0!important;
    padding: 0!important;
    width: 64px!important;
    height: 64px!important;
    z-index: 100!important;
    position: absolute!important;
    top: 50%!important;
    margin-top: -22px!important;
    left: 50%!important;
    margin-left: -30px!important;
    line-height: 1!important;
    font-style: normal!important;
    font-weight: normal!important;
    background-image: url('http://<?php print DOMAIN ?>/site/images/video_play.png')!important;
}
</style>

<section class="slider-numbers video image">
	<div class="w_row twelve-hun-max">
<!--	<h5 class="section-title small-12 columns fu_video_head1"><?php print $LEFT_HEAD ?></h5>
-->
		<div class="large-12 columns">
			<div class="w_row">
				<ul>
				  <li>
<img src="http://<?php print DOMAIN ?>/images/<?php print $VIDEOIMG_B_1 ?>" alt="" title="" class="fu_video_div_img" />
					<div class="orbit-caption fu_video_div">
						<p><a href="#" class="videoModal-1"><img src="/images/video_circle.png" alt="" title="" class="fu_video_circle" /></p><p><span class="fu_video_text"><?php 
$VIDEOS_B_1  = "%VIDEOS_B_1%";
print $VIDEOS_B_1;
?></span></a></p>
					</div>
				  </li>
				 
				</ul>
			</div>
		</div>
<!--		<a class="section-link" href="<?php print $RIGHT_HEAD ?>" title="">View All Videos</a>
-->
	</div>
</section>
<div id="videoModal-1" class="reveal-modal large">
		<div class="modal-content">
			<h2>Video 1</h2>
			<div class="flex-video">
			<div class="js-lazyYT" data-youtube-id="<?php print $videourl ?>" data-width="560" data-height="315" data-parameters="rel=0"></div>
<!--<iframe width="800" height="315" src="<?php print $VIDEOURL_B_1 ?>" style="border:none;" allowfullscreen></iframe>-->
			</div>

		</div>
		<a class="close-reveal-modal">x</a>
  </div>
  
  ---------------------------------------------------
  ORIGINIAL:
<?php
$VIDEOURL_B_1  = "%VIDEOURL_B_1%";
$VIDEOIMG_B_1  = "%VIDEOIMG_B_1%";
$LEFT_HEAD  = "%LEFT_HEAD%";
$RIGHT_HEAD  = "%RIGHT_HEAD%";
?>

<section class="slider-numbers video image">
	<div class="w_row twelve-hun-max">
<!--	<h5 class="section-title small-12 columns fu_video_head1"><?php print $LEFT_HEAD ?></h5>
-->
		<div class="large-12 columns">
			<div class="w_row">
				<ul>
				  <li>
					<img src="http://<?php print DOMAIN ?>/images/<?php print $VIDEOIMG_B_1 ?>" alt="" title="" class="fu_video_div_img" />
					<div class="orbit-caption fu_video_div">
						<p><a href="#" class="videoModal-1"><img src="/images/video_circle.png" alt="" title="" class="fu_video_circle" /></p><p><span class="fu_video_text"><?php 
$VIDEOS_B_1  = "%VIDEOS_B_1%";
print $VIDEOS_B_1;
?></span></a></p>
					</div>
				  </li>
				 
				</ul>
			</div>
		</div>
<!--		<a class="section-link" href="<?php print $RIGHT_HEAD ?>" title="">View All Videos</a>
-->
	</div>
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

  
  -------------------------------------------------
  SETTINGS
  -------------------------------------------------
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

</table>













