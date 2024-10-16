<?php
if (isset($record)) {
?>
<section style="overflow:hidden;">
        <div class="w_row twelve-hun-max">
<h5 class="section-title small-12 columns"><?php print processEditorContent($record->fu_ad_title1); ?></h5>
                <div class="large-12 columns">
                        <div class="w_row">
                                <ul class="list-style-type:none;">
                                  <li>
                                        <img src="/images/<?php print processEditorContent($record->fu_ad_src1a); ?>" alt="" title="" class="fu_ad_img_supp" />
                                        <div class="orbit-caption fu_ad_div_supp">
                                                <p class="fu_ad_larger_supp"><?php print processEditorContent($record->fu_ad_head); ?></p>
                                                <p class="fu_ad_smaller_supp"><?php print processEditorContent($record->fu_ad_text); ?></p>
                                                <p><a HREF="<?php print processEditorContent($record->fu_ad_tag_url); ?>" class="button" title=""><?php print processEditorContent($record->fu_ad_tag);$
                                        </div>
                                  </li>
                                </ul>
                        </div>
                </div>
<a class="section-link hide-for-small" href="<?php print processEditorContent($record->fu_ad_title_link); ?>" title=""><?php print processEditorContent($record->fu_ad_title2); ?></a>

        </div>
</section>
<?php
}
?>
