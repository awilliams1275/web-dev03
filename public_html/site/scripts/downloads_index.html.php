<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/doctype.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/stylesheets.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/metadata.php';
?>
        <meta name="dcterms.description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> files and documents available for download">
        <meta name="dcterms.subject" content="downloads, download, documents, pdf, word, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>">
        <meta name="dcterms.title" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Document and File Downloads">
        <meta name="description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> files and documents available for download">
        <meta name="keywords" content="downloads, download, documents, pdf, word, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>">
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/opening_javascript.php';
?>
        <title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/opening.php';
?>
                <p>The following document downloads are available for download. Downloads in PDF format can be read using <a href="http://get.adobe.com/reader/">Adobe Reader</a>.</p>
                <p>MS Word and Powerpoint files can be read by using their respective applications or any alternatives.</p>
<?php
    if (!empty($topDownloads)) {
?>
                <h2>Popular downloads</h2>
                <ul class="item-list">
<?php
        foreach($topDownloads as $item) {
            if ($item->url == '') {
                 $extension = $item->getFilenameExtension();
            }
            else {
                 $extension = $item->getURLExtension();
            }
?>
                    <li><a href="<?php print getSiteRootURL() . buildDownloadsURL(-1, $item->id); ?>" class="icon-<?php print strtolower(encodeHtml($extension)); ?>"><?php print encodeHtml($item->title); ?></a> <small>(<?php print encodeHtml($extension); ?>)</small></li>
<?php
        }
?>
                </ul>
<?php
    }

    //  must do here to ensure not using left nav version.
    $lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
    $allRootCategories = $lgclList->getTopLevelCategories();
    $rootCategories = filterCategoriesInUse($allRootCategories, DOWNLOADS_APPLIED_CATEGORIES_TABLE, true);

    if (!empty($rootCategories)) {
?>
                <h2>Browse downloads by category</h2>
<?php
        foreach ($rootCategories as $rootCat) {
            $subCats = filterCategoriesInUse($lgclList->getChildCategories($rootCat->id), DOWNLOADS_APPLIED_CATEGORIES_TABLE, true);
            $splitArray = splitArray($subCats);
?>
                        <h3>
                            <a href="<?php print getSiteRootURL() . buildDownloadsURL($rootCat->id); ?>"><?php print encodeHtml($rootCat->name); ?></a>
                        </h3>
<?php
            if (!empty($subCats)) {
?>
                        <ul class="item-list">
<?php
                foreach ($subCats as $subCat) {
?>
                            <li>
                                <a href="<?php print getSiteRootURL() . buildDownloadsURL($subCat->id); ?>"><?php print encodeHtml($subCat->name); ?></a>
                            </li>
<?php
                }
?>
                        </ul>
<?php
            }
        }
    }

    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/closing.php';
?>