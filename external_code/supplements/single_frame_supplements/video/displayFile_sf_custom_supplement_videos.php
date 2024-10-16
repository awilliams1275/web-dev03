<?php
if (isset($record)) {

//get the id number of the vieo URL to use in the lazyYT div tag
$str = processEditorContent($record->videourl_b_1);
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
<!--    <h5 class="section-title small-12 columns fu_video_head1"><?php print processEditorContent($record->left_head); ?></h5>
-->
                <div class="large-12 columns">
                        <div class="w_row">
                                <ul>
                                  <li>
                                        <img src="http://<?php print DOMAIN ?>/images/<?php print processEditorContent($record->videoimg_b_1); ?>" alt="" title="" class="fu_video_div_img" />
                                        <div class="orbit-caption fu_video_div">
                                                <p><a href="#" class="videoModal-1"><img src="/images/video_circle.png" alt="" title="" class="fu_video_circle" /></p><p><span class="fu_video_text"><?php print processEditorContent($record->videos_b_1); ?></span></a></p>
                                        </div>
                                  </li>
                                 
                                </ul>
                        </div>
                </div>
<!--            <a class="section-link" href="<?php print processEditorContent($record->right_head); ?>" title="">View All Videos</a>
-->
        </div>
</section>
<div id="videoModal-1" class="reveal-modal large">
                <div class="modal-content">
                        <h2>Video 1</h2>
                        <div class="flex-video">
						<div class="js-lazyYT" data-youtube-id="<?php print $videourl ?>" data-width="560" data-height="315" data-parameters="rel=0"></div>
                        </div>
                </div>
                <a class="close-reveal-modal">X</a>
  </div>
<?php
}
?>


ORIGINAL:
-------------------------------------------------------
<?php
if (isset($record)) {
?>
<section class="slider-numbers video image">
        <div class="w_row twelve-hun-max">
<!--    <h5 class="section-title small-12 columns fu_video_head1"><?php print processEditorContent($record->left_head); ?></h5>
-->
                <div class="large-12 columns">
                        <div class="w_row">
                                <ul>
                                  <li>
                                        <img src="http://<?php print DOMAIN ?>/images/<?php print processEditorContent($record->videoimg_b_1); ?>" alt="" title="" class="fu_video_div_img" />
                                        <div class="orbit-caption fu_video_div">
                                                <p><a href="#" class="videoModal-1"><img src="/images/video_circle.png" alt="" title="" class="fu_video_circle" /></p><p><span class="fu_video_text"><?php print processEditorContent($record->videos_b_1); ?></span></a></p>
                                        </div>
                                  </li>
                                 
                                </ul>
                        </div>
                </div>
<!--            <a class="section-link" href="<?php print processEditorContent($record->right_head); ?>" title="">View All Videos</a>
-->
        </div>
</section>
<div id="videoModal-1" class="reveal-modal large">
                <div class="modal-content">
                        <h2>Video 1</h2>
                        <div class="flex-video">
                                <iframe width="800" height="315" src="<?php print processEditorContent($record->videourl_b_1); ?>" style="border:none;" allowfullscreen></iframe>
                        </div>
                </div>
                <a class="close-reveal-modal">X</a>
  </div>
<?php
}
?>

