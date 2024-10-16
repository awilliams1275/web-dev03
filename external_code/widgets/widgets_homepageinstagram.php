--------------------------------------------------------------
FRONT-END
--------------------------------------------------------------
WITH CURL:

<div id="fu_instagram">

<div class="fu_headtext">
%FU_INSTA_TITLE%
</div>

<div class="fu_viewprograms">
<a href="%FU_INSTA_URL%">VIEW OUR INSTAGRAM PAGE</a></div>
<div style="clear:both;"></div>

<?php

$xml_feed_url = 'http://widget.stagram.com/rss/n/%FU_INSTA_SNAP%';

echo $xml_feed_url;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $xml_feed_url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$xml = curl_exec($ch);
curl_close($ch);
$anobii = new SimpleXMLElement($xml);

for ($i=0;$i<=4;$i++){
$imgs = $imgs . $anobii->channel->item[$i]->description;
}
echo $imgs;
?>
</div>
<div style="clear:both;"></div>

---------------------------------------------------------------
WITH MAGPIE:
<div id="fu_instagram">

<div class="fu_headtext">
%FU_INSTA_TITLE%
</div>

<div class="fu_viewprograms">
<a href="%FU_INSTA_URL%">VIEW OUR INSTAGRAM PAGE</a></div>
<div style="clear:both;"></div>

<?php
include_once('websections/JaduHomepageWidgetSettings.php');
require('/var/www/jadu/public_html/site/custom_scripts/magpie/magpie/rss_fetch.inc');

$fu_insta_name = "%FU_INSTA_NAME%";
$xml_feed_url = 'http://widget.stagram.com/rss/n/'.$fu_insta_name;

$rss = fetch_rss($xml_feed_url);

$fu_i = 0;
$fu_imgs = "";

foreach ($rss->items as $item ) {
	
	$fu_imgs   = $fu_imgs ."<a href=" .$item["link"] ."><img src=" .$item["url"] ."></a>";

$fu_i++;
if ($fu_i == 5) break;
}

echo $fu_imgs;

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
