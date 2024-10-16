<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/rollbar.php';
$config = array(
    // required
    'access_token' => '2fd451690ee1476a98e2c5526654ef39',
    // optional - environment name. any string will do.
    'environment' => 'production',
    // optional - path to directory your code is in. used for linking stack traces.
    'root' => $_SERVER['DOCUMENT_ROOT'] . '/site/'
);
Rollbar::init($config);

    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/lib.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/structure/breadcrumb.php';
    require_once 'websections/JaduAnnouncements.php';
    
    if (basename($_SERVER['SCRIPT_NAME']) == 'index.php' || Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
        require_once 'websections/JaduTrackedURLs.php';
        require_once 'websections/JaduTrackedURLResults.php';
    }

    $liveUpdate = getLiveAnnouncement();
    $trackedURLs = array();
    $hideColumn = (boolean) hideColumn();
    $script = basename($_SERVER['SCRIPT_NAME']);
    $accessibilitySettings = '';

    if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
        if (isset($_REQUEST['trackedURLRead'])) {
            $trackedURLResult = getTrackedURLResult(Jadu_Service_User::getInstance()->getSessionUserID(), $_REQUEST['trackedURLID']);
            confirmTrackedURLRead($trackedURLResult->id);
        }
        elseif (isset($_REQUEST['trackedURLID']) && is_numeric($_REQUEST['trackedURLID'])) {
            $trackedURL = getTrackedURL($_REQUEST['trackedURLID']);
            $trackedURLResult = getTrackedURLResult(Jadu_Service_User::getInstance()->getSessionUserID(), $trackedURL->id);
            if ($trackedURLResult->id == -1) {
                $trackedURLResult->userID = Jadu_Service_User::getInstance()->getSessionUserID();
                $trackedURLResult->trackedURLID = $trackedURL->id;
                newTrackedURLResult($trackedURLResult);
            }
            elseif ($trackedURLResult->confirmedRead > 0) {
                unset($trackedURL);
            }
        }
        if (basename($_SERVER['SCRIPT_NAME']) == 'index.php') {
            if (!Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
                $trackedURLs = getAllTrackedURLs(true);
            }
            else {
                $trackedURLs = getAllUnreadTrackedURLsForUser(Jadu_Service_User::getInstance()->getSessionUserID());
            }
        }
    }
    if (($script != 'documents_info.php' && $hideColumn == true) || ($script == 'documents_info.php' && $pageStructure->id == '2')) {
        $oneColumnLayout = true;
    }
    else {
        $oneColumnLayout = false;
    }
    if (isset($_COOKIE['userColourscheme']) && !empty($_COOKIE['userColourscheme'])) {
        switch ($_COOKIE['userColourscheme']) {
            case 'highcontrast':
                $accessibilitySettings .= ' user-scheme__high-contrast';
                break;
            case 'cream':
                $accessibilitySettings .= ' user-scheme__cream';
                break;
            case 'blue':
                $accessibilitySettings .= ' user-scheme__blue';
                break;
        }
    }
    if (isset($_COOKIE['userFontsize']) && !empty($_COOKIE['userFontsize'])) {
        switch ($_COOKIE['userFontsize']) {
            case 'small':
                $accessibilitySettings .= ' user-size__small';
                break;
            case 'medium':
                $accessibilitySettings .= ' user-size__medium';
                break;
            case 'large':
                $accessibilitySettings .= ' user-size__large';
                break;
        }
    }
    if (isset($_COOKIE['userFontchoice']) && !empty($_COOKIE['userFontchoice'])) {
        switch ($_COOKIE['userFontchoice']) {
            case 'comicsans':
                $accessibilitySettings .= ' user-font__comic-sans';
                break;
            case 'courier':
                $accessibilitySettings .= ' user-font__courier';
                break;
            case 'arial':
                $accessibilitySettings .= ' user-font__arial';
                break;
           case 'times':
                $accessibilitySettings .= ' user-font__times';
                break;
        }
    }
    if (isset($_COOKIE['userLetterspacing']) && !empty($_COOKIE['userLetterspacing'])) {
        switch ($_COOKIE['userLetterspacing']) {
            case 'wide':
                $accessibilitySettings .= ' user-spacing__wide';
                break;
            case 'wider':
                $accessibilitySettings .= ' user-spacing__wider';
                break;
            case 'widest':
                $accessibilitySettings .= ' user-spacing__widest';
                break;
        }
    }
?>
    </head>
    <body<?php if ($oneColumnLayout == true || !empty($accessibilitySettings)) { ?> class="<?php if ($oneColumnLayout == true) { ?>one-column<?php if ($script == 'documents.php') { ?> doc-one-column<?php } } if (!empty($accessibilitySettings)) { print ' '.trim($accessibilitySettings); } ?>"<?php } ?>>
    
    <div class="off-canvas-wrap">
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/structure/header.php';
    
    if ($liveUpdate->id != '' && $liveUpdate->id != -1) {
?>
        <div class="callout callout__warning announcement">
            <div>
            <p><strong><?php print encodeHtml($liveUpdate->title); ?></strong> <?php print encodeHtml($liveUpdate->content); ?>
<?php
        if ($liveUpdate->url != '') {
?>
            <a href="<?php print encodeHtml($liveUpdate->url); ?>"><?php print encodeHtml($liveUpdate->linkText); ?></a>
<?php
        }
?></p>
            </div>
        </div>
<?php
    }
    require_once HOME."site/includes/signpost/signpost.php";
    if (isset($trackedURL) && $trackedURL->id != -1) {
?>
        <div class="callout callout__info announcement">
            <h2>Have you read this page?</h2>
            <h3><?php print encodeHtml($trackedURL->title); ?></h3>
            <p><?php print $trackedURL->description; ?></p>
            <form action="<?php print getSiteRootURL(); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="trackedURLID" value="<?php print (int) $trackedURL->id; ?>">
                <input type="submit" class="button button__primary" name="trackedURLRead" value="I have read this page">
            </form>
        </div>
<?php
    }
    elseif (!empty($trackedURLs)) {
?>
        <div class="callout callout__info announcement">
            <h2>Have you read these pages?</h2>
            <ul class="item-list">
<?php
        foreach ($trackedURLs as $trackedURL) {
            $trackedURLID = 'trackedURLID=' . $trackedURL->id;
            if (mb_strpos($trackedURL->url, '?') === false) {
                $trackedURLID = '?' . $trackedURLID;
            }
            else {
                $trackedURLID = '&' . $trackedURLID;
            }
?>
                <li>
                    <a href="<?php print encodeHtml($trackedURL->url . $trackedURLID); ?>"><?php print encodeHtml($trackedURL->urlText); ?></a>
                </li>
<?php
        }
?>
            </ul>
        </div>
<?php
    }
?>

<?php
include_once(HOME."site/includes/signpost/signpost.php");
?>
    
<?php 
    if (!isset($indexPage) || !$indexPage) {
        if ($isSubsite && $oneColumnLayout && $script == 'documents.php') {
?>
        <aside class="show-for-small">
            <div class="grandparentnav">
                <a href="" title="" class="show-for-small in-section">In This Section</a>
<?php
        print \custom\Nav\Menu::findByCodename('left_hand_navigation', array(
                'depth' => 3,
                'maxUpDistance' => 2,
                'siblings' => false,
                'extendedFamily' => false,
                'onlyActiveBranch' => true,
                'relativeToReference' => true,
                'referenceParameters' => $_GET,
                'attributes' => array(
                    'ul' => array('class' => 'sidenav parentnav'),
                    'li' => array('class' => 'childmav')
                )
            ))->render();
?>
            </div>
        </aside>
<?php
        }
        else if ($oneColumnLayout == true && $script == 'home_info.php') {
?>
        <div id="content" class="group" role="main">
<?php            
        }
        else {
?>
    <div class="row twelve-hun-max main-wrap">
    <div class="small-12 columns">
    <div class="row">
<?php
            if ($oneColumnLayout == false) {
                require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/structure/column.php';
            }
            else if ($script == 'gallery_info.php' || $script == 'documents_info.php') {
?>
    <div class="content-padding">
<?php        
            }

            // don't show breadcrumb or title for independant homepages
            if (!(isset($homepage) && $homepage->independant == 1)) {
?>
    <div class="main universal" role="main">
<!-- googleoff: all -->
        <nav class="breadcrumbs">
<?php
                if (!empty($MAST_BREADCRUMB)) {
?>
                <?php print $MAST_BREADCRUMB; ?>
<?php
                }
?>
        </nav>
<!-- googleon: all -->
        <section class="content">
         <h1 class="title"><?php print encodeHtml($MAST_HEADING); ?></h1>
<?php
            }
        }
    }
    else {
?>
        <div class="main" role="main">
        <h1 class="visually-hidden"><?php print encodeHtml($MAST_HEADING); ?></h1>
<?php        
    }
?>
