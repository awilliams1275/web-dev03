<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/doctype.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/stylesheets.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/metadata.php';
?>
        <meta name="dcterms.description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Advanced search facilities">
        <meta name="dcterms.subject" content="search, advanced, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>">
        <meta name="dcterms.title" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Advanced search">
        <meta name="description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Advanced search facilities">
        <meta name="keywords" content="search, advanced, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>">
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/opening_javascript.php';
?>
        <title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/opening.php';

    //  Print none found error message
    if (isset($_GET['noResults']) && $_GET['noResults']) {
?>
                <div class="callout callout__error">
                    <h2>Your search returned no results</h2>
                    <p>Try using the search facility below to find what you were looking for.</p>
                </div>
<?php
    }
?>
                <form action="<?php print getSiteRootURL() . buildNonReadableSearchResultsURL('',true) ?>" method="post" enctype="multipart/form-data" class="form form__block">
                <ul class="list-none">
<?php
    $seen = array();

    foreach (array_keys($SEARCHABLE_TABLES) as $table) {
        $friendly = $SEARCHABLE_TABLES[$table]['FRIENDLY_NAME'];

        if (!in_array($friendly, $seen)) {
            $seen[] = $friendly;
?>
                    <li>
                        <label for="<?php print encodeHtml(str_replace(' ', '_', $friendly)); ?>" class="checkbox">
                            <input type="checkbox" id="<?php print encodeHtml(str_replace(' ','_',$friendly)); ?>" name="areas[]" class="areas_checkboxes" value="<?php print encodeHtml($friendly); ?>"> <?php print encodeHtml($friendly); ?>
                        </label>
                    </li>
<?php
                }
            }
?>
                </ul>
                    <div>
                        <label for="all">With all the words</label>
                        <input id="all" type="text" name="all" class="field">
                    </div>
                    <div>
                        <label for="without">Without the words</label>
                        <input id="without" type="text" name="without" class="field">
                    </div>
                    <div>
                        <label for="any">With any of these words</label>
                        <input id="any" type="text" name="any" class="field">
                    </div>
                    <div>
                        <label for="phrase">With the exact phrase</label>
                        <input id="phrase" type="text" name="phrase" class="field">
                    </div>
                    <div>
                        <input type="submit" value="Search" name="advancedSubmit" class="button button__info">
                    </div>
                </form>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/closing.php';
?>