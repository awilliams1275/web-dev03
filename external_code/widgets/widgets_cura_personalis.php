<?php
$curaxml=simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/xml/cura.xml");
$curx = 0;

$cura_titles = array();
$cura_by = array();

foreach ($curaxml->channel->item as $item)  {
$cura_titles[$curx] = $item->title;
$cura_by[$curx] = $item->author;
$curx = $curx+1;
}
$cura_head = "<strong>Cura Personalis</strong> is Fordham's commitment to educating the whole person.";
$cura_foot = "SUBMIT YOUR CURA PERSONALIS STORY";
?> 


<section class="slider-quotes">
<div class="row full-screen-width">
<div class="large-12 columns">
<div class="row">
<ul data-orbit class="orbit">
<li>
<div class="orbit-caption">
<p><?php echo $cura_head ?></p>
<blockquote><?php echo $cura_titles[0] ?></blockquote>
<p class="by"><?php echo $cura_by[0] ?></p>
<a class="submit"><?php echo $cura_foot ?></a>
</div>
</li>

<li>
<div class="orbit-caption">
<p><?php echo $cura_head ?></p>
<blockquote><?php echo $cura_titles[1] ?></blockquote>
<p class="by"><?php echo $cura_by[1] ?></p>
<a class="submit"><?php echo $cura_foot ?></a>
</div>
</li>

<li>
<div class="orbit-caption">
<p><?php echo $cura_head ?></p>
<blockquote><?php echo $cura_titles[2] ?></blockquote>
<p class="by"><?php echo $cura_by[2] ?></p>
<a class="submit"><?php echo $cura_foot ?></a>
</div>
</li>

<li>
<div class="orbit-caption">
<p><?php echo $cura_head ?></p>
<blockquote><?php echo $cura_titles[3] ?></blockquote>
<p class="by"><?php echo $cura_by[3] ?></p>
<a class="submit"><?php echo $cura_foot ?></a>
</div>
</li>

<li>
<div class="orbit-caption">
<p><?php echo $cura_head ?></p>
<blockquote><?php echo $cura_titles[4] ?> </blockquote>
<p class="by"><?php echo $cura_by[4] ?></p>
<a class="submit"><?php echo $cura_foot ?></a>
</div>
</li>
</ul>
</div>
</div>
</div>
</section>


