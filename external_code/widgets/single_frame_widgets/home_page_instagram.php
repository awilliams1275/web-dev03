<div id="fu_instagram">

<div class="fu_headtext">
%FU_INSTA_TITLE%
%FU_INSTA_TITLE2%
</div>

<div class="fu_viewprograms">
<a href="%FU_INSTA_URL%">VIEW OUR INSTAGRAM PAGE</a></div>
<div style="clear:both;"></div>

<?php
$fu_insta_name = "%FU_INSTA_NAME%";
$fu_insta_hash = "%FU_INSTA_HASH%";
$fu_insta_hash = str_replace("#",'',$fu_insta_hash);
$fu_insta_msg = false;
$fu_typeisusername = true;

//check: which is filled out, name or hash; if both use none
if (strlen($fu_insta_name) > 0){
  $url = ('https://www.instagram.com/'.$fu_insta_name.'/');
$fu_typeisusername = true;
}
elseif (strlen($fu_insta_hash) > 0){
$url = 'https://www.instagram.com/explore/tags/'. $fu_insta_hash.'/';
$fu_typeisusername = false;
}
else {
  $fu_insta_msg = true;
}
//---------------------------------------

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

if ($fu_typeisusername == true){
$thearray=($data->entry_data->ProfilePage[0]->user->media->nodes);
}else{
  $thearray=($data->entry_data->TagPage[0]->tag->media->nodes);
}
$imga = "";
$imgb = "";
$img_link = "";
$fu_count=0;

foreach ($thearray as $obj)
	{ $imga= $obj->thumbnail_src;
	  $img_link = $obj->code;

		$imgb= $imgb."<a href='https://www.instagram.com/p/".$img_link."'><img src='".$imga."' alt='Fordham University Instagram' title='Fordham University Instagram' /></a>";
		
		$fu_count++;
		if ($fu_count == 5) break;

	}
echo $imgb;
}
?>