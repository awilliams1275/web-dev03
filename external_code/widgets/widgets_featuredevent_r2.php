FRONT-END
----------------------------------------------------------------
<?php 
$fu_fp_fbtn_1  = "%FU_FP_FBTN_1%"; 
$fu_fp_fbtn_2  = "%FU_FP_FBTN_2%";


//if ( strpos($fu_fp_fbtn_2, "http") == 0){
//$fu_fp_fbtn_2 = "http://" .$fu_fp_fbtn_2;
//}
//echo $fu_fp_fbtn_2;

$feedurl = "%FU_FEEDURL%";
?>
<style>
.events.column{padding:1em;}

.events .column.gray{background-color:none;color:#8d002a;background:url(/site/styles/img/spotlight-gradient-overlay.png) no-repeat bottom right;padding:1.5em;font-size:100%;}

.events .column.red{background-color:none;color:#ffffff;background:url(/site/images/cura_gradient.png) no-repeat bottom right;padding:1.5em;font-size:100%;background-size:cover;}
.events .column.red a{color:#ffffff;}
</style>
<section class="events">
    <div class="w_row twelve-hun-max">
      <h5 class="section-title small-12 columns"><?php $fu_fp_fbtn_1  = "%FU_FP_FBTN_1%"; 
print $fu_fp_fbtn_1; ?>
</h5>
<div class="small-12 large-12 columns">

<?php
date_default_timezone_set('America/New_York');

//convert date/time
function convthedate($eventDateStr){
//capture today's date
$todaysdate= mktime(0, 0, 0, date("m"), date("d"), date("y"));
$todaysdate= date("Y-m-d", $todaysdate);

//the event's date
$date  = date_create($eventDateStr);
$date->modify('-4 hour');
$datecheck = $date->format("Y-m-d");


//is the event date and today's date the same?
if ($todaysdate == $datecheck) {
    $eventdate= "Today " .$date->format('\@ h:i A');}
    else
    {
     $eventdate= $date->format('D\, M jS \@ h:i A');
    }
if ( strpos ($eventdate, "12:00 AM") ) {
$eventdate = $date->format('D\, M jS');
}
return $eventdate;
}

$thedatetime = new DateTime;
//start date for records list
$thedatetime=$thedatetime->format("Y-m-d\T00:00-05:00");
//-------------------------------------------------------------------------

$temp = "";

$url =$feedurl ."/";
$ch = curl_init();                  
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);                 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$xml = curl_exec($ch);
curl_close($ch);
$i = 0;

$link = "";

//----------------------------------------------------------
// Word count and display for "description"
function words($text){
$text = explode(' ', $text);
$first_15_words = array_slice($text, 0, 15);
$words= implode(' ', $first_15_words);
return $words;
}
//----------------------------------------------------------


$xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA) ;

$fu_xmllen= strlen($xml);

if ($fu_xmllen === 0){
echo "Please enter a valid RSS Feed URL";
}else{

for ($i=0; $i<=4; $i++) {
$fu_febkg = ($i <= 1 ? "small-12 large-20p medium column red" : "small-12 large-20p column gray");

 if(!empty($xml->channel->item[$i]->link) || isset($xml->channel->item[$i]->link)){
     
$link=  "<a href='". $xml->channel->item[$i]->link ."'>";
   }


$temp = $temp ."<div class='".$fu_febkg."'>".$link;

   if(!empty($xml->channel->item[$i]->pubDate) || isset($xml->channel->item[$i]->pubDate)){
    $temp = $temp ."<div class='daytime'>" .convthedate($xml->channel->item[$i]->pubDate). "</div>";
   }
   if(!empty($xml->channel->item[$i]->title) || isset($xml->channel->item[$i]->title)){
     $temp= $temp . "<div class='summary'>". $xml->channel->item[$i]->title ."</div>";
   }
   if (!empty($xml->channel->item[$i]->description) || isset($xml->channel->item[$i]->description)){
     $temp= $temp . "<div class='description'>" . words($xml->channel->item[$i]->description) ." <nobr><em>...more >></em></nobr></div>";
   }
   $featureditems[$i] = $temp ."</a></div>";
   $temp = "";
}

}//end if $fu_xmllen statement 

//print out 
for ($y=0; $y<=4; $y++){
    echo $featureditems[$y];
}
    ?>

</div>
<a class="section-link" href="%FU_FP_FBTN_2%">VIEW ALL EVENTS</a>
        </div>
</section>


---------------------------------------------------------
SETTINGS
---------------------------------------------------------
<table class="form_table" id="tbl_widget_content"
<tr>
<td class="label_cell">Featured Event Name*</td>
<td class="data_cell"><input id="fu_fp_fbtn_1" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">View All Events Link*</td>
<td class="data_cell"><input id="fu_fp_fbtn_2" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell" style="text-align:left;">Enter Feed URL:*</td>
<td class="data_cell"><input type="text" id="fu_feedurl" value="" size="45"/>
</td></tr>
</table>



---------------------------------------------------------
SETTINGS JAVASCRIPT
---------------------------------------------------------
function commitWidgetLinks ()
{
 var fu_fp_fbtn_1 = $('fu_fp_fbtn_1').value; 
    if (fu_fp_fbtn_1 == '') {
        alert('Please enter the Event Name');
        return false;
}
{
 var fu_feedurl = $('fu_feedurl').value; 
    if (fu_feedurl == '') {
        alert('Please enter the Feed URL ');
        return false;
}

widgetItems[activeWidget].settings = new Object();
widgetItems[activeWidget].settings['fu_fp_fbtn_1'] = fu_fp_fbtn_1;
widgetItems[activeWidget].settings['fu_feedurl;'] = fu_feedurl;

return true;
}