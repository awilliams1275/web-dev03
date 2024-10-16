FRONT-END
--------------------------------------------------------
<?php
include_once('websections/JaduHomepageWidgetSettings.php');
$cura_head = "<strong>Cura Personalis</strong> is Fordham's commitment to educating the whole person.";

$cura_text = "%FU_CURA_TEXT%";
$cura_by = "%FU_CURA_BY%";
$cura_story = "%FU_CURA_STORY%";

?> 
<style>
#fu_cura_section{margin: 2em 0 2em 0;}
.fu_cura_bkg
{
 background: url(/site/images/cura_gradient.png) repeat;
background-size:contain;
background-position:center;
background-size: 100%;
color:#ffffff;
padding: 1em 0 1em 0;
}
blockquote {
 
    text-align:center;
 
}
 
blockquote p {
    font-size:200%;
    text-align:center;
    padding:0;
    color: #ffffff;
    display:block;
}
 
blockquote p.fu_cura_quote {
    font-size:200%;
    text-align:center;
    padding:0 0 10px 0px;
    position:relative;
}
 
blockquote p.fu_cura_quote:before, blockquote p.fu_cura_quote:after {
   content: "\201D";
    font-size: 200%;
    font-family:Georgia, "Times New Roman", Times, serif;
    color: #ffffff;
    font-weight:bold;
    
    line-height:0.8
}
 
blockquote p.fu_cura_quote:before {
   content: "\201C";
    
    
    top:8px;
}
.fu_cura_by{font-size:120%; }
</style>

<section id="fu_cura_section">
	<div class="w_row full-screen-width fu_cura_bkg">
		<div class="large-12 columns">
			<div class="w_row" style="list-style-type:none;text-align:center;">
				<div class="orbit-caption" style="color:#ffffff;">
					<p style="font-size:150%;"><strong>Cura Personalis</strong> is Fordham's commitment to educating the whole person.</p>
						<blockquote><p class="fu_cura_quote"><?php print $cura_text; ?></blockquote>
<p class="fu_cura_by"><?php print $cura_by; ?></p>
<a class="submit" style="color:#ffffff;font-size:85%;text-transform:uppercase;font-weight:bold;"><?php print $cura_story; ?></a>
</div>

</div>
</div>
</div>
</section>




--------------------------------------
SETTINGS
--------------------------------------
<table class="form_table" id="tbl_widget_content">
<input type="hidden" value="<?php print $DOMAIN; ?>" id="DOMAIN" />

<tr><td  class="data_cell" colspan="2" bgcolor="#ffffff">Cura Personalis</td></tr>

<tr><td class="label_cell">Text:</td><td class="data_cell"><input id="fu_cura_text" value="" size="45" type="text" /></td></tr>
<tr><td class="label_cell">Byline:</td><td class="data_cell"><input id="fu_cura_by" value="" size="45" type="text" /></td></tr>

<tr><td class="label_cell">Submit Story Tag Line:</td><td class="data_cell"><input id="fu_cura_story" value="" size="45" type="text" /></td></tr>
</table>




------------------------------------------
SETTINGS JAVASCRIPT
------------------------------------------
 if ($("fu_cura_story").value == '') {
        $("fu_cura_story").value == '';
        return;
    }
	
	
