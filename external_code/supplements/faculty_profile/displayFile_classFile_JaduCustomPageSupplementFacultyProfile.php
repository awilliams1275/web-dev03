<section class="profiles">
	<div class="row twelve-hun-max">
	<div class="small-12 columns">
		<h5 class="section-title small-12 columns">Faculty Profiles</h5>
<?php

        if (isset($record)) {

?>
<figure class="small-12 large-4 column red">
 <a href="<?php print encodeHtml($record->url); ?>">
        <img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($record->imageFilename); ?>" alt="<?php print encodeHtml(getImageProperty($record->imageFilename, 'altText')); ?>" />
	<figcaption>
	        <div class="title"><?php print processEditorContent($record->fp_name); ?></div>
        	<p><?php print processEditorContent($record->description); ?></p>
	</figcaption>
</a>
</figure>

<figure class="small-12 large-4 column red">
 <a href="<?php print encodeHtml($record->url2); ?>">
        <img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($record->imageFilename2); ?>" alt="<?php print encodeHtml(getImageProperty($record->imageFilename2, 'altText')); ?>" />
        <figcaption>
                <div class="title"><?php print processEditorContent($record->fp_name2); ?></div>
                <p><?php print processEditorContent($record->description2); ?></p>
        </figcaption>
</a>
</figure>

<figure class="small-12 large-4 column red">
 <a href="<?php print encodeHtml($record->url3); ?>">
        <img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($record->imageFilename3); ?>" alt="<?php print encodeHtml(getImageProperty($record->imageFilename3, 'altText')); ?>" />
        <figcaption>
                <div class="title"><?php print processEditorContent($record->fp_name3); ?></div>
                <p><?php print processEditorContent($record->description3); ?></p>
        </figcaption>
</a>
</figure>



<?php

        }
?>

</div>
</div>
</section>
