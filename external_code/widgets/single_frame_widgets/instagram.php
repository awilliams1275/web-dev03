http://162.209.24.221
CLIENT ID   f56ce420561247cfa5f636222e41a81c
CLIENT SECRET   e37fd84edbca495199e7cd626fa7c50d
WEBSITE URL http://162.209.24.221
REDIRECT URI    http://162.209.24.221
SUPPORT EMAIL   None
access_token=1216079274.f56ce42.cf06d48b2da549a1bb448a5528f91307 //162.
-------------------------------------
jaduuat
Client ID 94016dfc1d264876bb83224d84fb7164
Client Secret 9e3437db077d4edf897ac591f1f6c929 //jaduuat.fordham.edu
http://jaduuat.fordham.edu/site/index.php#access_token=1216079274.94016df.f453f46b307641b3a2eb8bb474d069af

https://instagram.com/oauth/authorize/?client_id=f56ce420561247cfa5f636222e41a81c&redirect_uri=http://162.209.24.221&response_type=token

https://instagram.com/oauth/authorize/?client_id=94016dfc1d264876bb83224d84fb7164&redirect_uri=http://jaduuat.fordham.edu&scope=public_content&response_type=token

----------------------------------------
www.fordham.edu
CLIENT ID   32d85c246cbf44c3a1c051b6dc56c812
CLIENT SECRET   12560a958b724021a28c2be2b6b53796
WEBSITE URL http://www.fordham.edu
REDIRECT URI    http://www.fordham.edu

https://instagram.com/oauth/authorize/?client_id=32d85c246cbf44c3a1c051b6dc56c812&redirect_uri=http://www.fordham.edu&scope=public_content&response_type=token
http://www.fordham.edu/site/index.php#access_token=1216079274.32d85c2.412759f6b99e4fe1a4fd6461d0c15ae5

Scope=public_content: The app will be used only by Fordham University web developers to display Instagram content from all of Fordham's various Instagram accounts on Fordham university web pages.
------------------------------------------------------

<style>
.instaimg{padding:0 1 0 1;}
</style>
<?php
function getInstaID($username)
{
$username = strtolower($username); // sanitization

$url = "http://jelled.com/ajax/instagram?do=username&username=".$username."&format=json";
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result  = curl_exec($ch);
curl_close($ch);

$result = json_decode($result);


switch ($username){

    case "fordhamuniversity":;
    return "377152067";
    break;

    default:

    foreach($result->data as $user)
    {
       if ($user->username == $username){
        return $user->id;}     
    }//end foreach

}//end switch

}

$inid = getInstaID('fordhamuniversity');



$url2 = "https://api.instagram.com/v1/users/".$inid."/media/recent/?access_token=1216079274.f56ce42.cf06d48b2da549a1bb448a5528f91307";

$ch = curl_init($url2);

curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result2  = curl_exec($ch);
curl_close($ch);

$result2 = json_decode($result2);
$display_size = "thumbnail";
$count = 1;

    foreach ($result2->data as $photo) {
        $img = $photo->images->{$display_size};
        if ( $count <= 5){

        echo "<a href='{$photo->link}'><img src='{$img->url}' / class=instaimg></a>";
        $count = $count +1;
}
else{break;}
}
?>