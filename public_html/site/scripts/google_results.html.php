<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/doctype.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/stylesheets.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/metadata.php';
?>
        <meta name="dcterms.description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>'s search results">
        <meta name="dcterms.subject" content="<?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>">
        <meta name="dcterms.title" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Search results">
        <meta name="description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>'s search results">
        <meta name="keywords" content="<?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>">
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/opening_javascript.php';
?>
        <title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/opening.php';
?>
     <script>
  (function() {
    var cx = '009439241125018188426:oudisfjkwfa';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:search top_refinements="0"></gcse:search>

<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/closing.php';
?>