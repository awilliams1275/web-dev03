<?php
    require_once 'utilities/JaduStatus.php';
    require_once 'JaduStyles.php';
    require_once 'JaduSearch.php';

    // Breadcrumb, H1 and Title
    $MAST_HEADING = 'Advanced search';
    $MAST_BREADCRUMB = '
                    <a href="' . getSiteRootURL() . '" rel="home">Home</a>
                    <a href="#" class="current">Advanced search</a>';

    require_once 'search_index.html.php';
?>