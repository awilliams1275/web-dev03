<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/doctype.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/stylesheets.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/metadata.php';
?>
        <meta name="dcterms.description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> download - <?php print encodeHtml($download->title); foreach ($dirTree as $parent) { ?> | <?php print encodeHtml($parent->name); } ?>">
        <meta name="dcterms.subject" content="downloads, download, documents, pdf, word, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>">
        <meta name="dcterms.title" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> download - <?php print encodeHtml($download->title); foreach ($dirTree as $parent) { ?> | <?php print encodeHtml($parent->name); } ?>">
        <meta name="description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> download - <?php print encodeHtml($download->title); foreach ($dirTree as $parent) { ?> | <?php print encodeHtml($parent->name); } ?>">
        <meta name="keywords" content="downloads, download, documents, pdf, word, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>">
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/opening_javascript.php';
?>
        <title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/opening.php';

    if (!isset($download) || $download->id < 1) {
?>
                <div class="callout callout__warning">
                    <h2>Sorry, this download is no longer available</h2>
                </div>
<?php
    }
    /* Removing retail functionality as it's never used, placeholder text means this is a dead-cert *
    else if ($addToBasket) {
?>
    <h2><?php print encodeHtml($fileItem->title);?></h2>
    <p>Some text explaining the download is purchasable as a product and the user should add the product to there basket</p>
    <ul>
<?php
        if (!empty($download->description)) {
?>
        <li><?php print nl2br(encodeHtml($download->description)); ?></li>
<?php
        }
?>
        <li>Size: <?php print $fileItem->getHumanReadableSize(); ?></li>
        <li>Extension: <span class="icon-<?php print strtolower(encodeHtml($extension)); ?>"><?php print encodeHtml($extension); ?></span></li>
    </ul>
    <form enctype="multipart/form-data" action="http://<?php print DOMAIN . buildRetailBasketURL(); ?>" method="post" name="basketform">
        <input type="submit" name="add_to_basket" value="Add to basket" />
        <input type="hidden" name="action_basket" value="ADD" />
        <input type="hidden" name="id" value="<?php print $product->id; ?>" />
        <input type="hidden" name="q" value="1" />
    </form>
<?php
    }
    else if ($showProductLink) {
?>
    <h2><?php print encodeHtml($fileItem->title);?></h2>
    <p>Some text explaining the download is purchasable as can be purchased through multiple products, <a href="/site/scripts/retail_products_with_download.php?downloadFileID=<?php print intval($_GET['fileID']); ?>">linking to the products list page</a>.</p>
<?php
        if (!empty($download->description)) {
?>
    <p><?php print nl2br(encodeHtml($download->description)); ?></p>
<?php
        }
    }
    */
    else if ($showDownload) {
        if (isset($fileItem) && $fileItem->id > 0) {
            if ($fileItem->url == '') {
                $extension = $fileItem->getFilenameExtension();
                $filename = 'http://' . $DOMAIN . buildDownloadsURL(-1, $fileItem->id, $download->id, true);
            }
            else {
                $filename = encodeHtml($fileItem->url);
                $extension = $fileItem->getURLExtension();
            }
?>
                <h2><?php print encodeHtml($fileItem->title); ?> (<?php print encodeHtml($extension); ?> - <?php print $fileItem->getHumanReadableSize(); ?>)</h2>
<?php
            if (!empty($download->description)) {
?>
                <p><?php print nl2br(encodeHtml($download->description)); ?></p>
<?php
            }
?>
                <a href="<?php print $filename; ?>" class="button button__success">Download now</a>
<?php
        }
        else if (!empty($allFiles)) {
?>
                <p><?php print nl2br(encodeHtml($download->description)); ?></p>
                 <ul class="item-list">
<?php
            foreach ($allFiles as $fileItem) {
                if ($fileItem->url == '') {
                    $extension = $fileItem->getFilenameExtension();
                    $path = 'http://'. DOMAIN . buildDownloadsURL(-1, $fileItem->id, $download->id);
                }
                else {
                    $extension = $fileItem->getURLExtension();
                    $path = $fileItem->url;
                }
?>
                        <li>
                            <a href="<?php print encodeHtml($path); ?>"><?php print encodeHtml($fileItem->title); ?> (<?php
                if ($extension != '') {
?>
                            <?php print encodeHtml($extension); ?> - 
<?php
                }
?><?php print $fileItem->getHumanReadableSize(); ?>)</a>
                        </li>

<?php
            }
?>
                 </ul>
<?php
        }
    }
    else {

?>
                <div class="callout callout__warning">
                    <h2>This download is restricted</h2>
                    <form name="downloadLoginForm" id="downloadLoginForm" method="post" enctype="multipart/form-data" action="<?php print getSiteRootURL() . buildNonReadableDownloadsURL(-1, isset($fileItem) ? $fileItem->id : -1, $download->id); ?>" class="form form__inline">
                        <h3>Please enter the password</h3>
                        <div>
                            <label for="password" class="visually-hidden">Password</label>
                            <input type="password" name="password" id="password" value="" placeholder="Password">
                            <input type="submit" name="submitDownloadLogin" id="submitDownloadLogin" value="Access download" class="button">
                        </div>
                    </form>
                </div>
<?php
    }

    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/closing.php';
?>