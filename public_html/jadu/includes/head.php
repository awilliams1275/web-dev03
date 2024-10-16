<?php include_once('utilities/ACL.php'); ?>
<!DOCTYPE html>
<!--[if IE 7]>         <html class="no-js ie7 lt-ie10 lt-ie8" id="pulsar"><![endif]-->
<!--[if IE 8]>         <html class="no-js ie8 lt-ie10 lt-ie9" id="pulsar"><![endif]-->
<!--[if IE 9]>         <html class="no-js ie9 lt-ie10" id="pulsar"><![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" id="pulsar"><!--<![endif]-->
<head>
    <meta name="content-type" content="text/html; charset=utf-8" />
    <meta name="content-language" content="en" />
    <title>Jadu Control Center<?php
        if (!empty($moduleName)) {
            print ' : '. strip_tags($moduleName);
            if (!empty($modulePageName)) {
                print ' : ' . htmlentities($modulePageName);
            }
        }
    ?></title>
    
    <?php include_once(MAIN_HOME_DIR . 'public_html/jadu/bundles/pulsar/views/pulsar/components/favicons-cms.html'); ?>

    <link rel="stylesheet" type="text/css" href="<?php print $SECURE_JADU_PATH ?>/pulsar/repainted/css/layout.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php print $SECURE_JADU_PATH ?>/pulsar/repainted/css/ui.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php print $SECURE_JADU_PATH ?>/pulsar/repainted/assets/css/header.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php print $SECURE_JADU_PATH ?>/pulsar/repainted/assets/css/pulsar.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php print $SECURE_JADU_PATH ?>/pulsar/repainted/css/lightbox.css" media="all" />

<!--[if lte IE 6]>
    <link rel="stylesheet" type="text/css" href="<?php print $SECURE_JADU_PATH; ?>/css/ie.css" />
<![endif]-->
<!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="<?php print $SECURE_JADU_PATH; ?>/css/ie_seven.css" />
<![endif]-->
<!--[if IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php print $SECURE_JADU_PATH; ?>/css/ie_eight.css" />
<![endif]-->

<?php
    if (defined('CONTROL_CENTRE_BRANDING_STYLESHEET') && CONTROL_CENTRE_BRANDING_STYLESHEET != '') {
?>
    <link rel="stylesheet" type="text/css" href="<?php print encodeHtml(CONTROL_CENTRE_BRANDING_STYLESHEET); ?>" media="all" />
<?php
    }

    if (defined('TEXT_DIRECTION') && TEXT_DIRECTION == 'rtl') {
?>
    <link rel="stylesheet" type="text/css" href="<?php print $SECURE_JADU_PATH ?>/pulsar/repainted/css/ui-rtl.css" media="all" />
<?php
    }

    popCSS();
?>
    <link rel="stylesheet" type="text/css" href="<?php print $SECURE_JADU_PATH; ?>/bundles/pulsar/css/pulsar-legacy.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php print $SECURE_JADU_PATH; ?>/bundles/pulsar/css/navigation.css" media="all" />

    <script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/javascript/prototype.js"></script>
    <script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/javascript/builder.js"></script>
    <script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/javascript/effects.js"></script>
    <script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/javascript/global.js"></script>
    <script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/javascript/jadu_effects.js"></script>
    <script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/javascript/cc_switch.js"></script>
    <script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/javascript/swfobject.js"></script>
    <!-- New Pulsar js -->
    <script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/assets/pulsar/js/ui.js"></script>
    <!-- quick_search.js must be included before popJavascript to allow main search page to overwrite key events -->
    <script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/search/javascript/quick_search.js"></script>

<?php
    if (!isset($javascriptAdminView) || $javascriptAdminView !== 'true') {
        $javascriptAdminView = 'false';
    }

    print outputJavascriptConstants($javascriptAdminView);

    addJavascript('javascript/lightbox.js');
    addJavascript('javascript/dragdrop.js');
    addJavascript('javascript/who_is_online.js');

    popJavascript();

    if (isset($statusMessage) && !empty($statusMessage)) {
        print '<script type="text/javascript">addLoadEvent(function () { HighlightTextTone(\'st_msg\', \'#339900\', \'#ffffff\', 4, 6); });</script>';
    }
?>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/bundles/pulsar/libs/html5shiv/dist/html5shiv.js"></script>
    <script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/bundles/pulsar/libs/nwmatcher/src/nwmatcher.js"></script>
    <script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/bundles/pulsar/libs/selectivizr/selectivizr.js"></script>
    <script type="text/javascript" src="<?php print $SECURE_JADU_PATH; ?>/bundles/pulsar/libs/respond/dest/respond.min.js"></script>
    <![endif]-->
</head>
<body id="<?php print encodeHtml($bodyId); ?>">
    <div role="main" class="container">
        <div class="inner-wrapper">

<?php include('pulsar_navigation.php'); ?>
<?php include('pulsar_toolbar.php'); ?>

            <div class="flash-container js-flash-container">
<?php
    if (!empty($statusMessage) || $statusMessage = Jadu_Service_Container::getInstance()->getSuccessMessage()) {
?>
                <div class="flash flash--success">
                    <a class="close" href="#" data-dismiss="flash"><i class="icon-remove"></i></a>
                    <i class="icon-ok"></i>
                    <?php print encodeHtml($statusMessage); ?>
                </div>
<?php
    }
?>
            </div>

<?php
    if (!$passwordReset) {
?>
            <form action="<?php print $SECURE_JADU_PATH; ?>/search" class="main-search" data-ui="search-box" >
				<input type="search" id="cc_search_q"  value="<?php print (isset($cc_searchQuery)) ? encodeHtml($cc_searchQuery) : ''; ?>" placeholder="Search" class="main-search__field" autocomplete="off">
				<button class="main-search__submit" id="cc_search_go_button" type="submit" name="case-search"><i class="icon-search"></i></button>
				<div id="cc_search_loader" class="loading loading--circle hide"><i>Loading...</i></div>
				<div id="cc_search_results" style="display:none;">
					<br class="clear" />
				</div>
			</form>
<?php
    }
?>
            <!-- <div id="wrap_content" class="sticky-container"> -->

                <div class="content-main">
                <div class="tabs__content">

<?php include('breadcrumb.php'); ?>
<?php include('pulsar_title.php'); ?>
