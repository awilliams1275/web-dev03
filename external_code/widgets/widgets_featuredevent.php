ICAL TO XML CONVERSION SCRIPT:
<?php

function iCalendarToXML($icalendarData) {

    // Detecting line endings
    if (strpos($icalendarData,"\r\n")) $lb = "\r\n";
    elseif (strpos($icalendarData,"\n")) $lb = "\n";
    else $lb = "\r\n";

    // Splitting up items per line
    $lines = explode($lb,$icalendarData);

    // Properties can be folded over 2 lines. In this case the second
    // line will be preceeded by a space or tab.
    $lines2 = array();
    foreach($lines as $line) {

        if ($line[0]==" " || $line[0]=="\t") {
            $lines2[count($lines2)-1].=substr($line,1);
            continue;
        }

        $lines2[]=$line;

    }

    $xml = '<?xml version="1.0"?>' . "\n";

    $spaces = 0;
    foreach($lines2 as $line) {

        $matches = array();
        // This matches PROPERTYNAME;ATTRIBUTES:VALUE
        if (preg_match('/^([^:^;]*)(?:;([^:]*))?:(.*)$/',$line,$matches)) {
            $propertyName = strtoupper($matches[1]);
            $attributes = $matches[2];
            $value = $matches[3];

            // If the line was in the format BEGIN:COMPONENT or END:COMPONENT, we need to special case it.
            if ($propertyName == 'BEGIN') {
                $xml.=str_repeat(" ",$spaces);
                $xml.='<' . strtoupper($value) . ">\n";
                $spaces+=2;
                continue;
            } elseif ($propertyName == 'END') {
                $spaces-=2;
                $xml.=str_repeat(" ",$spaces);
                $xml.='</' . strtoupper($value) . ">\n";
                continue;
            }

            $xml.=str_repeat(" ",$spaces);
            $xml.='<' . $propertyName;
            if ($attributes) {
                // There can be multiple attributes
                $attributes = explode(';',$attributes);
                foreach($attributes as $att) {

                    list($attName,$attValue) = explode('=',$att,2);
                    $xml.=' ' . $attName . '="' . htmlspecialchars($attValue) . '"';

                }
            }

            $xml.='>'. htmlspecialchars($value) . '</' . $propertyName . ">\n";

        }

    }

    return $xml;

}

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/calendar/ical/fordham.edu_15ku36e217aahvd5ukh23etdds%40group.calendar.google.com/public/basic.ics");

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

$calendarxml = iCalendarToXML($output);

$pathtoxmlfile = "/var/www/jadu/public_html/xml/calendar.xml";

$myfile = fopen($pathtoxmlfile, "w") or die("Unable to open file!");
fwrite($myfile, $calendarxml);
fclose($myfile);

chgrp($pathtoxmlfile,"jadu-www");
chown($pathtoxmlfile,"jadu");
chmod($pathtoxmlfile,0755);

?>


FRONT-END
---------------------------------------------------------------------------
R1:
--------------------------------------------------------------------------
<?php
$xml=simplexml_load_file("/var/www/jadu/public_html/xml/calendar.xml") or die("Error: Cannot create object");
$i = 0;
$arr = array();

foreach($xml->children() as $event) {

$tmp =  $event->DESCRIPTION;

if (strlen($tmp) == 0 ){
$i = $i;

} else{

$cal_time =  $event->DTSTART;
$cal_time = "<time>" .date( "D\, M j \@ h:i a", strtotime( $cal_time ) ). "</time>" ;

$cal_sum = "<div class=\"title\">" .$event->SUMMARY ."</div>";

$cal_desc =  $event->DESCRIPTION;
$cal_desc =  str_replace("\,", ",", $cal_desc);
$cal_desc =  "<p>" .substr(str_replace("\\n", "<br />", $cal_desc),0,100)."...</p>" ;

$arr[] = $cal .$cal_time ."<br />" .$cal_sum ."<br />" .$cal_desc;

$i=$i+1;
if ($i==5){break;}
}
}

$arr = array_reverse($arr);

?>

<section class="events">
		<div class="w_row twelve-hun-max">
			<h5 class="section-title small-12 columns"><?php $fu_fp_fbtn_1  = "%FU_FP_FBTN_1%"; 
print $fu_fp_fbtn_1;
?></h5>


<div class="small-12 large-20p medium column red">
<?php echo $arr[0]; ?>
</div>

<div class="small-12 large-20p medium column red">
<?php echo $arr[1]; ?>
</div>

<div class="small-12 large-20p column gray">
<?php echo $arr[2]; ?>
</div>

<div class="small-12 large-20p column gray">
<?php echo $arr[3]; ?>
</div>

<div class="small-12 large-20p column gray">
<?php echo $arr[4]; ?>
</div>

<a class="section-link" href="<?php $fu_fp_fbtn_2  = "%FU_FP_FBTN_2%";
print $fu_fp_fbtn_2;
?>">VIEW ALL EVENTS</a>
		</div>
	</section>

	
	--------------------------------------------------------------
	R0:
-------------------------------------------------------------------

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
<section class="events">
		<div class="row twelve-hun-max">
			<h5 class="section-title small-12 columns"><?php $fu_fp_fbtn_1  = "%FU_FP_FBTN_1%"; 
print $fu_fp_fbtn_1;
?></h5>


<div class="small-12 large-20p medium column red">
<time>%FU_FP_DDT_1%</time>
<div class="title">%FU_FP_TITLE_1%</div>
<p>%FU_FP_DESC_1%</p>
</div>

<div class="small-12 large-20p medium column red">
<time>%FU_FP_DDT_2%</time>
<div class="title">%FU_FP_TITLE_2%</div>
<p>%FU_FP_DESC_2%</p>
</div>

<div class="small-12 large-20p column gray">
<time>%FU_FP_DDT_3%</time>
<div class="title">%FU_FP_TITLE_3%</div>
<p>%FU_FP_DESC_3%</p>
</div>

<div class="small-12 large-20p column gray">
<time>%FU_FP_DDT_4%</time>
<div class="title">%FU_FP_TITLE_4%</div>
<p>%FU_FP_DESC_4%</p>
</div>

<div class="small-12 large-20p column gray">
<time>%FU_FP_DDT_5%</time>
<div class="title">%FU_FP_TITLE_5%</div>
<p>%FU_FP_DESC_5%</p>
</div>


<a class="section-link" href="<?php $fu_fp_fbtn_2  = "%FU_FP_FBTN_2%";
print $fu_fp_fbtn_2;
?>">VIEW ALL EVENTS</a>
		</div>
	</section>

	
--------------------------------------------------------------------
SETTINGS
--------------------------------------------------------------------
	
<table class="form_table" id="tbl_widget_content"
<tr>
<td class="label_cell">Featured Event Name*</td>
<td class="data_cell"><input id="fu_fp_fbtn_1" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">View All Events Link*</td>
<td class="data_cell"><input id="fu_fp_fbtn_2" value="" size="45" type="text" />
</td></tr>

<tr><td class="data_cell" colspan="2" style="text-align:left;">First Event</td></tr>
<tr><td class="label_cell">Day/Date/Time*</td>
<td class="data_cell"><input id="fu_fp_ddt_1" value="" size="45" type="text" />
</td></tr>
<tr>
<td class="label_cell">Title*</td>
<td class="data_cell"><input id="fu_fp_title_1" value="" size="45" type="text" />
</td></tr>

<tr><td class="label_cell">Description*</td>
<td class="data_cell"><input id="fu_fp_desc_1" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Link</td>
<td class="data_cell"><input id="fu_fp_link_1" value="" size="45" type="text" />
</td></tr>

<tr><td class="data_cell" colspan="2">Second Event</td></tr>
<tr><td class="label_cell">Day/Date/Time*</td>
<td class="data_cell"><input id="fu_fp_ddt_2" value="" size="45" type="text" />
</td></tr>
<tr>
<td class="label_cell">Title*</td>
<td class="data_cell"><input id="fu_fp_title_2" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Description*</td>
<td class="data_cell"><input id="fu_fp_desc_2" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Link</td>
<td class="data_cell"><input id="fu_fp_link_2" value="" size="45" type="text" />
</td></tr>

<tr><td class="data_cell" colspan="2">Third Event</td></tr>
<tr><td class="label_cell">Day/Date/Time*</td>
<td class="data_cell"><input id="fu_fp_ddt_3" value="" size="45" type="text" />
</td></tr>
<tr>
<td class="label_cell">Title*</td>
<td class="data_cell"><input id="fu_fp_title_3" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Description*</td>
<td class="data_cell"><input id="fu_fp_desc_3" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Link</td>
<td class="data_cell"><input id="fu_fp_link_3" value="" size="45" type="text" />
</td></tr>

<tr><td class="data_cell" colspan="2">Fourth Event</td></tr>
<tr><td class="label_cell">Day/Date/Time*</td>
<td class="data_cell"><input id="fu_fp_ddt_4" value="" size="45" type="text" />
</td></tr>
<tr>
<td class="label_cell">Title*</td>
<td class="data_cell"><input id="fu_fp_title_4" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Description*</td>
<td class="data_cell"><input id="fu_fp_desc_4" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Link</td>
<td class="data_cell"><input id="fu_fp_link_4" value="" size="45" type="text" />
</td></tr>

<tr><td class="data_cell" colspan="2">Fifth Event</td></tr>
<tr><td class="label_cell">Day/Date/Time*</td>
<td class="data_cell"><input id="fu_fp_ddt_5" value="" size="45" type="text" />
</td></tr>
<tr>
<td class="label_cell">Title*</td>
<td class="data_cell"><input id="fu_fp_title_5" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Description*</td>
<td class="data_cell"><input id="fu_fp_desc_5" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Link</td>
<td class="data_cell"><input id="fu_fp_link_5" value="" size="45" type="text" />
</td></tr></table>


--------------------------------------------------------------------
SETTINGS JAVASCRIPT
--------------------------------------------------------------------
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
 var fu_fp_fbtn_1 = $('fu_fp_fbtn_1').value; 
    if (fu_fp_fbtn_1 == '') {
        alert('Please enter the Event Name');
        return false;
    }
	
	var fu_fp_fbtn_2 = $('fu_fp_fbtn_2').value; 
    if (fu_fp_fbtn_2 == '') {
        alert('Please enter the View All Events link URL');
        return false;
    }
    var fu_fp_ddt_1 = $('fu_fp_ddt_1').value; 
    if (fu_fp_ddt_1 == '') {
        alert('Please enter a Day/Date/Time for Event 1.');
        return false;
    }
	
	 var fu_fp_title_1 = $('fu_fp_title_1').value; 
    if (fu_fp_title_1 == '') {
        alert('Please enter a title for Event 1.');
        return false;
    }
	var fu_fp_desc_1 = $('fu_fp_desc_1').value; 
    if (fu_fp_desc_1 == '') {
        alert('Please enter a Description for Event 1.');
        return false;
    }
	
	var fu_fp_ddt_2 = $('fu_fp_ddt_2').value; 
    if (fu_fp_ddt_2 == '') {
        alert('Please enter a Day/Date/Time for Event 2.');
        return false;
    }	
	 var fu_fp_title_2 = $('fu_fp_title_2').value; 
    if (fu_fp_title_2 == '') {
        alert('Please enter a title for Event 2.');
        return false;
    }
	var fu_fp_desc_2 = $('fu_fp_desc_2').value; 
    if (fu_fp_desc_2 == '') {
        alert('Please enter a Description for Event 2.');
        return false;
    }
	
	var fu_fp_ddt_3 = $('fu_fp_ddt_3').value; 
    if (fu_fp_ddt_3 == '') {
        alert('Please enter a Day/Date/Time for Event 3.');
        return false;
    }	
	 var fu_fp_title_3 = $('fu_fp_title_3').value; 
    if (fu_fp_title_3 == '') {
        alert('Please enter a title for Event 3.');
        return false;
    }
	var fu_fp_desc_3 = $('fu_fp_desc_3').value; 
    if (fu_fp_desc_3 == '') {
        alert('Please enter a Description for Event 3.');
        return false;
    }
	
var fu_fp_ddt_4 = $('fu_fp_ddt_4').value; 
    if (fu_fp_ddt_4 == '') {
        alert('Please enter a Day/Date/Time for Event 4.');
        return false;
    }	
	 var fu_fp_title_4 = $('fu_fp_title_4').value; 
    if (fu_fp_title_4 == '') {
        alert('Please enter a title for Event 4.');
        return false;
    }
	var fu_fp_desc_4 = $('fu_fp_desc_4').value; 
    if (fu_fp_desc_4 == '') {
        alert('Please enter a Description for Event 4.');
        return false;
    }
var fu_fp_ddt_5 = $('fu_fp_ddt_5').value; 
    if (fu_fp_ddt_5 == '') {
        alert('Please enter a Day/Date/Time for Event 5.');
        return false;
    }	
	 var fu_fp_title_5 = $('fu_fp_title_5').value; 
    if (fu_fp_title_5 == '') {
        alert('Please enter a title for Event 5.');
        return false;
    }
	var fu_fp_desc_5 = $('fu_fp_desc_5').value; 
    if (fu_fp_desc_5 == '') {
        alert('Please enter a Description for Event 5.');
        return false;
    }
  
    widgetItems[activeWidget].settings = new Object();
    widgetItems[activeWidget].settings['fu_fp_fbtn_1'] = fu_fp_fbtn_1;

widgetItems[activeWidget].settings['fu_fp_ddt_1'] = fu_fp_ddt_1;
	widgetItems[activeWidget].settings['fu_fp_title_1'] = fu_fp_title_1;
	widgetItems[activeWidget].settings['fu_fp_desc_1'] = fu_fp_desc_1;
	widgetItems[activeWidget].settings['fu_fp_link_1'] = fu_fp_link_1;
	
	widgetItems[activeWidget].settings['fu_fp_fbtn_2'] = fu_fp_fbtn_2;
	widgetItems[activeWidget].settings['fu_fp_ddt_2'] = fu_fp_ddt_2;
	widgetItems[activeWidget].settings['fu_fp_title_2'] = fu_fp_title_2;
	widgetItems[activeWidget].settings['fu_fp_desc_2'] = fu_fp_desc_2;
	widgetItems[activeWidget].settings['fu_fp_link_2'] = fu_fp_link_2;
	
	widgetItems[activeWidget].settings['fu_fp_ddt_3'] = fu_fp_ddt_3;
	widgetItems[activeWidget].settings['fu_fp_title_3'] = fu_fp_title_3;
	widgetItems[activeWidget].settings['fu_fp_desc_3'] = fu_fp_desc_3;
	widgetItems[activeWidget].settings['fu_fp_link_3'] = fu_fp_link_3;
	
	widgetItems[activeWidget].settings['fu_fp_ddt_4'] = fu_fp_ddt_4;
	widgetItems[activeWidget].settings['fu_fp_title_4'] = fu_fp_title_4;
	widgetItems[activeWidget].settings['fu_fp_desc_4'] = fu_fp_desc_4;
	widgetItems[activeWidget].settings['fu_fp_link_4'] = fu_fp_link_4;
	
	widgetItems[activeWidget].settings['fu_fp_ddt_5'] = fu_fp_ddt_5;
	widgetItems[activeWidget].settings['fu_fp_title_5'] = fu_fp_title_5;
	widgetItems[activeWidget].settings['fu_fp_desc_5'] = fu_fp_desc_5;
	widgetItems[activeWidget].settings['fu_fp_link_5'] = fu_fp_link_5;
   

    return true;
}
	