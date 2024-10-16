<?php
    require_once 'utilities/JaduStatus.php';
    require_once 'JaduStyles.php';
    require_once 'JaduLibraryFunctions.php';
    require_once 'rupa/JaduRupaAppliance.php';
    require_once 'rupa/JaduRupaSearch.php';
    require_once 'rupa/JaduRupaCollection.php';
    require_once 'rupa/JaduRupaRenameResultURL.php';
    require_once 'JaduMimeTypeList.php';
    require_once 'JaduFileTypeMappings.php';
    require_once 'websections/JaduDownloadFiles.php';

    define('PAGE_SEARCH_RESULT_COUNT', 10);
    define('MAXIMUM_NAV_PAGE_COUNT', 10);

    if ((!isset($_GET['q']) || $_GET['q'] == '') &&
        (!isset($_GET['quoteQuery']) || $_GET['quoteQuery'] == '') &&
        (!isset($_GET['orQuery']) || $_GET['orQuery'] == '') &&
        (!isset($_GET['excludeWords']) || $_GET['excludeWords'] == '')) {

        header('Location: ' . getSiteRootURL() . '/site/scripts/google_advanced.php');
        exit;
    }

    $appliances = getRupaAppliances('live', '1');

    if (!empty($appliances)) {
        $appliance = $appliances[0];
    }
    else {
        // no live appliance, send to jadu search?

    }

    $allRupaRenameResultURLs = getAllRupaRenameResultURLs();
    $currentRupaSearch = new RupaSearch();

    // add the default collection and all advanced search collections
    $allCollections = array();
    $defaultCollection = getRupaCollectionByName($appliance->defaultCollection);
    $defaultCollection->friendlyName = 'All results';
    $allCollections[] = $defaultCollection;
    $allCollections = array_merge($allCollections, getRupaAdvancedSiteSearchCollections());

    $query = '';

    if (isset($_GET['q'])) {
        if (isset($_GET['pre_q'])) {
            $query.= $_GET['pre_q'].' ';
        }
        $query .= $_GET['q'];

        $currentRupaSearch->setQuery($query);
    }
	
	

    if (isset($_GET['quoteQuery'])) {
        $currentRupaSearch->setQuoteQuery($_GET['quoteQuery']);
    }

    if (isset($_GET['orQuery'])) {
        $currentRupaSearch->setOrQuery($_GET['orQuery']);
    }

    if (isset($_GET['excludeWords'])) {
        $currentRupaSearch->setExcludeWords($_GET['excludeWords']);
    }

    if (isset($_GET['fileFormat']) && $_GET['fileFormat'] != '') {
        $currentRupaSearch->addFileFormat($_GET['fileFormat']);
        if (isset($_GET['fileFormatInclusion'])) {
            $currentRupaSearch->setFileFormatInclusion($_GET['fileFormatInclusion']);
        }
    }

    if (isset($_GET['startNum'])) {
        $currentRupaSearch->setStartNum(intval($_GET['startNum']));
    }

    if (isset($_GET['numToShow'])) {
        $currentRupaSearch->setNumToShow(intval($_GET['numToShow']));
    }

    if (isset($_GET['sortBy'])) {
        $currentRupaSearch->setSortBy($_GET['sortBy']);
    }

    if (defined('RUPA_RESULT_TITLE_METADATA_FIELD') && RUPA_RESULT_TITLE_METADATA_FIELD != '') {
        $currentRupaSearch->addMetadataFieldsName(RUPA_RESULT_TITLE_METADATA_FIELD);
    }

    if (defined('RUPA_RESULT_SNIPPET_METADATA_FIELD') && RUPA_RESULT_SNIPPET_METADATA_FIELD != '') {
        $currentRupaSearch->addMetadataFieldsName(RUPA_RESULT_SNIPPET_METADATA_FIELD);
    }

    $defaultRupaSearch = $currentRupaSearch->getClone();
    $defaultRupaSearch->addSite($defaultCollection->collectionName);
    foreach ($allCollections as $collection) {
        $defaultRupaSearch->addSite($collection->collectionName);
    }
    $defaultRupaSearch->allSites = true;

    if (is_array($_GET['sites']) && !empty($_GET['sites'])) {
        $allowedCollections = getRupaAdvancedSiteSearchCollections();
        $allowedCollectionNames = array();
        foreach ($allowedCollections as $collection) {
            $allowedCollectionNames[] = $collection->collectionName;
        }
        foreach($_GET['sites'] as $collection) {
            if (in_array($collection, $allowedCollectionNames, true)) {
                $currentRupaSearch->addSite($collection);
            }
        }
    }
    else {
        $currentRupaSearch = $defaultRupaSearch->getClone();
    }

    $currentRupaSearch->setDefaultFilter(FILTER_SNIPPET_ONLY);

    if (!isset($_GET['filter'])) {
        $currentRupaSearch->setFilterResults(FILTER_SNIPPET_ONLY);
    }
    else {
        $currentRupaSearch->setFilterResults($_GET['filter']);
    }

    $resultCount = 0;

    $currentRupaSearchResult = $currentRupaSearch->search();
    if ($currentRupaSearchResult->documentsFound > 0) {
        $resultCount = $currentRupaSearchResult->documentsFound;
    }

    // check to ensure that the appliance is live, if not then redirect to Jadu search
    if ($currentRupaSearchResult == null && $currentRupaSearch->appliance == null) {
        header('Location: search_results.php?q=' . encodeHtml($_GET['q']));
        exit;
    }

    $collectionsForRefine = array();
    if ($currentRupaSearchResult->documentsFound > 0) {
        $collectionImageLinks = array();
        foreach($allCollections as $coll){
            $nextCollectionQuery = $currentRupaSearch->getClone();
            $nextCollectionQuery->clearSites();
            $nextCollectionQuery->addSite($coll->collectionName);
            $nextCollectionQuery->setStartNum(-1);
            $nextCollectionQueryResult = $nextCollectionQuery->search();
            $queryString = $nextCollectionQuery->getRupaQueryStringForXHTML();
            $collectionImageLinks[$coll->friendlyName] = getSiteRootURL() . '/site/scripts/google_results.php?' . $queryString;

            if ($nextCollectionQueryResult->documentsFound > 0) {
                $collectionsForRefine[] = array($coll, $queryString);
            }
        }
    }

    // Breadcrumb, H1 and Title
    $MAST_HEADING = 'Search results';
    $MAST_BREADCRUMB = '
                    <a href="' . getSiteRootURL() . '" rel="home">Home</a>
                    <a href="#" class="current">Search results</a>';

    require_once 'google_results.html.php';
?>