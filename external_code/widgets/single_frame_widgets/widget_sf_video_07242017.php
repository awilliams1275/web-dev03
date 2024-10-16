<?php

$VIDEOURL_B_1  = "%VIDEOURL_B_1%";
$VIDEOURL_B_19  = "%VIDEOURL_B_19%";
$VIDEOIMG_B_1  = "%VIDEOIMG_B_1%";
$LEFT_HEAD  = "%LEFT_HEAD%";
$RIGHT_HEAD  = "%RIGHT_HEAD%";

//get the id number of the vieo URL to use in the lazyYT div tag
$str = $VIDEOURL_B_1 ;
$out = array();
$arr = explode('/', $str);

$result = count($arr);
$videourl =  $arr[$result-1];

?>
<style>

.lazyYT-title {
    z-index: 100!important;
    color: #ffffff!important;
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
    background-image: url('//<?php print DOMAIN ?>/site/images/video_play.png')!important;
background-repeat:no-repeat;
}

#player {
        box-sizing: border-box;
       /*background: url('//i.imgur.com/vq812Tr.png') center center no-repeat;*/
        background-size: contain;
        padding:6% 15.5% 8%;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
}

.youtube-container { display: block; margin: 20px auto; width: 100%; max-width: 600px; }
.youtube-player { display: block; width: 100%;border:0px; /* assuming that the video has a 16:9 ratio */ padding-bottom: 56.25%; overflow: hidden; position: relative; width: 100%; height: 100%; cursor: hand; cursor: pointer; display: block; }
img.youtube-thumb { bottom: 0; display: block; left: 0; margin: auto; max-width: 100%; width: 100%; position: absolute; right: 0; top: 0; height: auto }
div.play-button { height: 72px; width: 72px; left: 50%; top: 50%; margin-left: -36px; margin-top: -36px; position: absolute; background: url("//i.imgur.com/TxzC70f.png") no-repeat; }
#youtube-iframe { width: 100%; height: 100%; position: absolute; top: 0; left: 0; }
.flex-video{padding-top:0;}

</style>
<section class="slider-numbers video image">
  <div class="w_row twelve-hun-max">
<!--  <h5 class="section-title small-12 columns fu_video_head1"><?php print $LEFT_HEAD ?></h5>
-->
    <div class="large-12 columns">
      <div class="w_row">
        <ul>
          <li>
<img src="//<?php print DOMAIN ?>/images/<?php print $VIDEOIMG_B_1 ?>" alt="video" title="<?php print $VIDEOIMG_B_1 ?>" class="fu_video_div_img" />
  <div class="orbit-caption fu_video_div">
  <p><a href="#" class="videoModal-1"><img src="/images/video_circle.png" alt="play" title="play video" class="fu_video_circle" /></p><p><span class="fu_video_text"><?php 
$VIDEOS_B_1  = "%VIDEOS_B_1%";
print $VIDEOS_B_1;
?></span></a></p>
  </div>
  </li>      
  </ul>
  </div>
  </div>
<!--    <a class="section-link" href="<?php print $RIGHT_HEAD ?>" title="">View All Videos</a>
-->
  </div>
</section>
<script>
 function newSource() {
var theclient = "";

               var isMobile = {

                   Android: function () {

                       return navigator.userAgent.match(/Android/i) ? true : false;

                   },

                   BlackBerry: function () {

                       return navigator.userAgent.match(/BlackBerry/i) ? true : false;

                   },

                   iOS: function () {

                       return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false;

                   },

                   Windows: function () {

                       return navigator.userAgent.match(/IEMobile/i) ? true : false;

                   },

                   any: function () {

                       return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Windows());

                   }

               };
if (!isMobile.any()) {


       var height = "100%";
var width = "100%";
document.getElementById('player').innerHTML= ('<iframe width="' + width + '" height="' + height + '" src="//www.youtube.com/embed/<?php echo $videourl?>?rel=0&autoplay=0&autohide=0&wmode=transparent"  style="position:absolute; top:0; left:0; width:100%; height:100%;" allowFullScreen frameborder=0></iframe>');
    }
else{ 
function newSource(){
 var height = "100%";
var width = "100%";
document.getElementById('player').innerHTML= ('');
}

 }
}
</script>

<div id="videoModal-1" class="reveal-modal large">
    <div class="modal-content">
      <div class="flex-video">
<div class="js-lazyYT"  data-youtube-id="<?php print $videourl ?>" ratio="5:4" data-parameters="rel=0" id="player"></div>
  
<div class="youtube-container">
<div class="youtube-player" id ="youtube-player" data-id="<?php print $videourl ?>" style="width:100%;"></div>
</div>

</div>

    </div>
<a class="close-reveal-modal" id="close" onclick="newSource();">x</a>
  </div>