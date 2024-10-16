--------------------------------------------------------------
FRONT-END
--------------------------------------------------------------
WITH CURL:

<style>
.fu_instaimg{padding:0 1 0 1;
width:18%;}
</style>
<div id="fu_instagram">

<div class="fu_headtext">
%FU_INSTA_TITLE%
</div>

<div class="fu_viewprograms">
<a href="%FU_INSTA_URL%">VIEW OUR INSTAGRAM PAGE</a></div>
<div style="clear:both;"></div>

<?php
$fu_insta_name = "%FU_INSTA_NAME%";
$url = ('https://www.instagram.com/'.$fu_insta_name.'/');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$html  = curl_exec($ch);
curl_close($ch);


$isavail = strpos($html, 'Sorry, this page'); //check for valid content

//if content exists, then display
if ($isavail <= 0){

$html = strstr($html, 'window._sharedData = ');

$html = strstr($html, '</script>', true);
//$html = substr($html,0,-6);
$html = substr($html, 20, -1);

$data = json_decode($html);
//echo var_dump(json_encode($html));



$thearray=($data->entry_data->ProfilePage[0]->user->media->nodes);
$imga = "";
$imgb = "";
$img_link = "";
$fu_count=0;

foreach ($thearray as $obj)
    { $imga= $obj->thumbnail_src;
      $img_link = $obj->code;

        $imgb= $imgb."<a href='https://www.instagram.com/p/".$img_link."'><img src='".$imga."' /></a>";
        
        $fu_count++;
        if ($fu_count == 5) break;

    }
echo $imgb;
}
?>
</div>
<div style="clear:both;"></div>


------------------------------------------------------
SETTINGS
------------------------------------------------------
<table class="form_table" id="tbl_widget_content">
<input type="hidden" value="<?php print $DOMAIN; ?>" id="DOMAIN" />

<tr>
<td class="label_cell">Instagram Title </td>
<td class="data_cell"><input id="fu_insta_title" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Instagram Page URL</td>
<td class="data_cell"><input id="fu_insta_url" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Instagram Name</td>
<td class="data_cell"><input id="fu_insta_name" value="" size="45" type="text" />
</td></tr>

</table>


------------------------------------------------------
SETTINGS JAVASCRIPT
------------------------------------------------------

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
 
	
    var fu_insta_title = $('fu_insta_title').value; 
    if (fu_insta_title == '') {
        alert('Please enter a title');
        return false;
    }
	
    var fu_insta_url = $('fu_insta_url').value; 
    if (fu_insta_url == '') {
        alert('Please enter the Instagram page URL');
        return false;
    }
	var fu_insta_name = $('fu_insta_name').value; 
    if (fu_insta_name == '') {
        alert('Please enter feed name');
        return false;
    }
	
    widgetItems[activeWidget].settings = new Object();
    widgetItems[activeWidget].settings['fu_insta_title'] = fu_insta_title;
    widgetItems[activeWidget].settings['fu_insta_url'] = fu_insta_url;
    widgetItems[activeWidget].settings['fu_insta_name'] = fu_insta_name;
	
    return true;
}
