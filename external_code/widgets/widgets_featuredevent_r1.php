<?php 
$fu_fp_fbtn_1  = "%FU_FP_FBTN_1%"; 
$fu_fp_fbtn_2  = "%FU_FP_FBTN_2%";

$feedtype = "%FU_FEED%";
$feedurl = "%FU_FEEDURL%";
?>
<style>
.events .column.gray{background-color:none;color:#8d002a;background:url(/site/styles/img/spotlight-gradient-overlay.png) no-repeat bottom right;}
.events .column.red{background-color:none;color:#ffffff;background:url(/site/images/cura_gradient.png) no-repeat bottom right;}
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
$datecheck = $date->format("Y-m-d");

//is the event date and today's date the same?
if ($todaysdate == $datecheck) {
    $eventdate= "Today " .$date->format('\@ h:i:s A');}
    else
    {
     $eventdate= $date->format('D\, M jS \@ h:i:s A');
    }
return $eventdate;
}

$thedatetime = new DateTime;
//start date for records list
$thedatetime=$thedatetime->format("Y-m-d\T00:00:00-05:00");
//-------------------------------------------------------------------------


//Google Calendar Feed:

if ($feedtype == "fu_google"){

$url = ('https://www.googleapis.com/calendar/v3/calendars/'.$feedurl.'/events?singleEvents=true&orderBy=startTime&maxResults=5&timeMin='.$thedatetime.'&alt=json&key=AIzaSyA_Y1Q-JRHdOMqpwLhkqiQCR_ueZ988aic');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$json = curl_exec($ch);
curl_close($ch);


$jf = json_decode($json, true);
$temp = "";
$htmlLink = "";

for ($i=0; $i<=4; $i++) {
$fu_febkg = ($i <= 1 ? "small-12 large-20p medium column red" : "small-12 large-20p column gray");

 $temp = $temp .'<div class="'.$fu_febkg.'">';

   if(!empty($jf['items'][$i]['htmlLink']) || isset($jf['items'][$i]['htmlLink'])){
 //   $htmlLink =  "<a href='" .$jf['items'][$i]['htmlLink']. "'>";
   }


   if(!empty($jf['items'][$i]['start']['dateTime']) || isset($jf['items'][$i]['start']['dateTime'])){
    $temp = $temp. $htmlLink ."<div class='daytime'>" .convthedate($jf['items'][$i]['start']['dateTime']). "</div>";
   }
 if(!empty($jf['items'][$i]['summary']) || isset($jf['items'][$i]['summary'])){
     $temp= $temp . "<div class='summary'>". $jf['items'][$i]['summary'] ."</div>";
   }
   if (!empty($jf['items'][$i]['description']) || isset($jf['items'][$i]['description'])){
     $temp= $temp .  "<div class='description'>" .substr($jf['items'][$i]['description'],0,50) ." <nobr><em>...more >></em></nobr></div>";
   }
  
   
   $featureditems[$i] = $temp ."</div>";
   $temp = "";
}//end for statement

}//if feedtype is an rss feed:
elseif ($feedtype == "fu_rss"){

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

$xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA) or die("Error: Cannot create object") ;

for ($i=0; $i<=4; $i++) {
$fu_febkg = ($i <= 1 ? "small-12 large-20p medium column red" : "small-12 large-20p column gray");


$temp = $temp ."<div class='".$fu_febkg."'>";

   if(!empty($xml->channel->item[$i]->pubDate) || isset($xml->channel->item[$i]->pubDate)){
    $temp = $temp ."<div class='daytime'>" .convthedate($xml->channel->item[$i]->pubDate). "</div>";
   }
   if(!empty($xml->channel->item[$i]->title) || isset($xml->channel->item[$i]->title)){
     $temp= $temp . "<div class='summary'>". $xml->channel->item[$i]->title ."</div>";
   }
   if (!empty($xml->channel->item[$i]->description) || isset($xml->channel->item[$i]->description)){
     $temp= $temp . "<div class='description'>" .substr($xml->channel->item[$i]->description,0,50) ." <nobr><em>...more >></em></nobr></div>";
   }
   $featureditems[$i] = $temp ."</div>";
   $temp = "";
}

}//end if feedtype   rss

else{
    echo "Enter A Feed Type";
}//end if feedtype   

//print out 
for ($y=0; $y<=4; $y++){
    echo $featureditems[$y];
}
    ?>

</div>
<a class="section-link" href="<?php echo $fu_fp_fbtn_2;
?>">VIEW ALL EVENTS</a>
        </div>
</section>


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
<td class="label_cell" colspan="2" style="text-align:left;">Select Feed Type:*</td></tr>
<tr><td class="data_cell" colspan="2">

<input type ="radio" id="fu_feed_1" name="fu_feed" value="fu_rss" onclick="showfeedurl_rss();" /> RSS Feed<br />
<input type ="radio" id="fu_feed_2" name="fu_feed" value="fu_google" onclick="showfeedurl_google();" /> Google Calendar Feed<br /><br />
<input type="hidden" id="fu_feed" />

<div id="feedurl_rss">
Enter RSS Feed URL:<br />
<input type="text" input id="fu_feedurl_1"  />
</div>

<div id="feedurl_google">
Enter Email Address Associated with the Google Calendar's Account:<br />
<input type="text" input id="fu_feedurl_2"  />
</div>
<input type="hidden" id="fu_feedurl" />
</td></tr>
</table>



function showfeedurl_rss(){
document.getElementById('feedurl_rss').style.visibility = "visible";
document.getElementById('feedurl_rss').style.display = "inline";
document.getElementById('feedurl_google').style.visibility = "hidden";
document.getElementById('feedurl_google').style.display = "none";
document.getElementById('fu_feed').value = document.getElementById('fu_feed_1').value;
document.getElementById('fu_feedurl').value = document.getElementById('fu_feedurl_1').value;
}

function showfeedurl_google(){
document.getElementById('feedurl_google').style.visibility = "visible";
document.getElementById('feedurl_google').style.display = "inline";
document.getElementById('feedurl_rss').style.visibility = "hidden";
document.getElementById('feedurl_rss').style.display = "none";
document.getElementById('fu_feed').value = document.getElementById('fu_feed_2').value;
document.getElementById('fu_feedurl').value = document.getElementById('fu_feedurl_2').value;
}

if ( document.getElementsByName('fu_feed')[0].checked == true ) {
document.getElementById('feedurl_rss').style.visibility = "visible";
document.getElementById('feedurl_rss').style.display = "inline";
document.getElementById('feedurl_google').style.visibility = "hidden";
document.getElementById('feedurl_google').style.display = "none";
document.getElementById('feedurl_google').value = "";
}
else if (document.getElementsByName('fu_feed')[1].checked == true) {
document.getElementById('feedurl_google').style.visibility = "visible";
document.getElementById('feedurl_google').style.display = "inline";
document.getElementById('feedurl_rss').style.visibility = "hidden";
document.getElementById('feedurl_rss').style.display = "none";
document.getElementById('feedurl_rss').value = "";
}else{
document.getElementById('feedurl_google').style.visibility = "hidden";
document.getElementById('feedurl_google').style.display = "none";
document.getElementById('feedurl_rss').style.visibility = "hidden";
document.getElementById('feedurl_rss').style.display = "none";
}


function commitWidgetLinks ()
{
 var fu_fp_fbtn_1 = $('fu_fp_fbtn_1').value; 
    if (fu_fp_fbtn_1 == '') {
        alert('Please enter the Event Name');
        return false;
}

widgetItems[activeWidget].settings = new Object();
widgetItems[activeWidget].settings['fu_fp_fbtn_1'] = fu_fp_fbtn_1;

return true;
}