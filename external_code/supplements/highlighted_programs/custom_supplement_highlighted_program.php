<section class="profiles">
	<div class="row twelve-hun-max">
	<div class="small-12 columns">
		<h5 class="section-title small-12 columns"><?php print processEditorContent($record->title); ?></h5>
<?php

        if (isset($record)) {

?>
<figure class="small-12 large-4 column red">
 <a href="<?php print encodeHtml($record->url_1); ?>">
        <img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($record->imageFilename_1); ?>" alt="<?php print encodeHtml(getImageProperty($record->imageFilename_1, 'altText')); ?>" />
	<figcaption>
	        <div class="title"><?php print processEditorContent($record->fp_name_1); ?></div>
        	<p><?php print processEditorContent($record->description_1); ?></p>
	</figcaption>
</a>
</figure>

<figure class="small-12 large-4 column red">
 <a href="<?php print encodeHtml($record->url_2); ?>">
        <img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($record->imageFilename_2); ?>" alt="<?php print encodeHtml(getImageProperty($record->imageFilename_2, 'altText')); ?>" />
        <figcaption>
                <div class="title"><?php print processEditorContent($record->fp_name_2); ?></div>
                <p><?php print processEditorContent($record->description_2); ?></p>
        </figcaption>
</a>
</figure>

<figure class="small-12 large-4 column red">
 <a href="<?php print encodeHtml($record->url_3); ?>">
        <img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($record->imageFilename_3); ?>" alt="<?php print encodeHtml(getImageProperty($record->imageFilename_3, 'altText')); ?>" />
        <figcaption>
                <div class="title"><?php print processEditorContent($record->fp_name_3); ?></div>
                <p><?php print processEditorContent($record->description_3); ?></p>
        </figcaption>
</a>
</figure>


<?php

        }
?>

</div>
</div>
</section>
