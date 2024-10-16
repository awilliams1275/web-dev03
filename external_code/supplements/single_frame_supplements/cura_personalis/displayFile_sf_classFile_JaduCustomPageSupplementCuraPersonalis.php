<?php

if (isset($record)) {

?>
<section id="fu_cura_section">
	<div class="w_row full-screen-width fu_cura_bkg">
		<div class="large-12 columns">
			<div class="w_row" style="list-style-type:none;text-align:center;">
				<div class="orbit-caption" style="color:#ffffff;">
					<p style="font-size:150%;"><strong>Cura Personalis</strong> is Fordham's commitment to educating the whole person.</p>
						<blockquote><p class="fu_cura_text"><?php print processEditorContent($record->fu_cura_text); ?></blockquote>
<p class="fu_cura_by"><?php print processEditorContent($record->fu_cura_by); ?></p>
<a class="submit" style="color:#ffffff;font-size:85%;text-transform:uppercase;font-weight:bold;"><?php print processEditorContent($record->fu_cura_url); ?><?php print processEditorContent($record->fu_cura_story); ?></a>
</div>

</div>
</div>
</div>
</section>

<?php

}
?>


