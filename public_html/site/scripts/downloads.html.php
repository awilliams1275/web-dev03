<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/doctype.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/stylesheets.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/metadata.php';
?>
        <meta name="dcterms.description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>'s Files and documents available for download within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>">
        <meta name="dcterms.subject" content="<?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ', '; } ?><?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>">
        <meta name="dcterms.title" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> downloads<?php foreach ($dirTree as $parent) { ?> | <?php print encodeHtml($parent->name); } ?>">
        <meta name="description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>'s Files and documents available for download within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>">
        <meta name="keywords" content="<?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ', '; } ?><?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>">
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
    if (!empty($allDownloads)) {
?>
                <h2>Available downloads</h2>
<?php
        foreach ($allDownloads as $downloadItem) {
            $allFiles = getAllDownloadFilesForDownload($downloadItem->id, array('live' => true, 'approved' => true));
            if (!empty($allFiles)) {
?>
                        <h3>
                            <a href="<?php print getSiteRootURL() . buildDownloadsURL(-1, -1, $downloadItem->id); ?>"><?php print encodeHtml($downloadItem->title); ?></a>
                        </h3>
<?php
                if (!empty($download->description)) {
?>
                            <p><?php print nl2br(encodeHtml($download->description)); ?></p>
<?php
                }
?>
                    <ul class="item-list">
<?php
                foreach ($allFiles as $fileItem) {
                    if ($fileItem->url == '') {
                        $extension = $fileItem->getFilenameExtension();
                        $path = getSiteRootURL() . buildDownloadsURL($_REQUEST['categoryID'], $fileItem->id, $downloadItem->id, false);
                    }
                    else {
                        $extension = $fileItem->getURLExtension();
                        $path = encodeHtml($fileItem->url);
                    }
?>
                            <li>
                                <a href="<?php print $path; ?>" class="icon-<?php print strtolower(encodeHtml($extension)); ?>"><?php print encodeHtml($fileItem->title); ?></a> (<?php print encodeHtml($extension); ?> - <?php print $fileItem->getHumanReadableSize(); ?>)
                            </li>
<?php
                }
?>
                        </ul>
<?php
            }
        }
    }
    if (!empty($categories)) {
?>
                <h2>Sub-categories of <?php print encodeHtml($parent->name); ?></h2>
                <ul class="item-list">
<?php
        foreach ($categories as $subCat) {
?>
                    <li>
                        <a href="<?php print getSiteRootURL() . buildDownloadsURL($subCat->id) ?>"><?php print encodeHtml($subCat->name); ?></a>
                    </li>
<?php
        }
?>
                </ul>
<?php
    }
?>
                <a href="<?php print getSiteRootURL() . buildCategoryRSSURL("downloads", $_GET['categoryID']); ?>" class="icon-rss"><?php print encodeHtml(METADATA_GENERIC_NAME . ' ' . $currentCategory->name); ?> RSS feed</a>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/closing.php';
?>