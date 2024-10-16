<?php
if (isset($record)) {
?>

<section class="slider-numbers image" style="overflow:hidden;padding:0;">
        <div class="w_row twelve-hun-max">
                <h5 class="section-title small-12 columns"><?php print processEditorContent($record->title); ?></h5>
                <div class="large-12 columns">
                        <div class="w_row">

                                        <img src="http://<?php print DOMAIN ?>/images/<?php print processEditorContent($record->imageFilename2); ?>" alt="" title="" class="img_src" style="position:relative;" />
                                        <div class="orbit-caption fu_fbtn_div" style="height:100%;">
                                                <p" style="height:100%;"><img src="http://<?php print DOMAIN ?>/images/<?php print processEditorContent($record->imageFilename); ?>" alt="" title="" class="fu_fbt$
                                                <p class="fu_fbtn_text"><?php print processEditorContent($record->fu_img_text1); ?></p>
                        </div>
                        </div>
                </div>
                <a class="section-link hide-for-small" href="%FU_FBTN_2%" title="" style="position:absolute;"><?php print processEditorContent($record->fu_fbtn_2_text); ?></a>
        </div>
</section>
<?php
}
?>

