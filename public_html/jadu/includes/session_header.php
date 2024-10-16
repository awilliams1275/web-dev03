<?php
    include_once("JaduConstants.php");
    require_once('secure_session.php');
    include_once("utilities/JaduLicenses.php");
    include_once("utilities/JaduAdministrators.php");
    require_once("galaxies/JaduGalaxiesSites.php");
    require_once("galaxies/JaduGalaxiesURL.php");
    require_once("galaxies/JaduGalaxiesUser.php");

    // Allow to be overridden by modules e.g. XFP
    $performRedirect = true;

    $path = Jadu_Service_Container::getInstance()->getRequest()->getUri()->getPath();
    $exts = explode('.', $path);
    $ext = end($exts);

    $ext_bypass = array('css','js','png','jpg','gif','jpeg','webm','ogv','swf','txt','mp4','mp3','robots','html','htm','json','xml','docx','doc','pdf','xls','xlsx');
    $bypass = array('jadu/login', 'jadu/login/verify', 'jadu/forgot-password', 'jadu/reset-password');

    if(in_array($ext, $ext_bypass)) {
        return false;
    }

    // Don't cause a redirect loop
    foreach($bypass as $ignore) {
        if(stristr($path, $ignore) !== false) {
            return false;
        }
    }

    $moduleDir = dirname(substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], '.php')));

    // Include any module specific session headers
    if (file_exists(HOME.$moduleDir.'/includes/session_header.php')) {
        include_once(HOME.$moduleDir.'/includes/session_header.php');
    }

    if (file_exists(HOME.'jadu/custom/custom_includes.php')) {
        include_once(HOME.'jadu/custom/custom_includes.php');
    }

    $adminService = Jadu_Service_Container::getInstance()->getJaduAdministrator();
    // check license has not been violated
    if ($adminService->getAdministratorCount() > getMaxAdminsAllowed()) {
        header("Location: /jadu/forbidden.php?reason=license");
        exit();
    }

    if (defined('SHOW_CC_PAGE_LOAD_TIME') && SHOW_CC_PAGE_LOAD_TIME) {
        $startTimes = explode(" ", microtime());
        $startTime = $startTimes[0] + $startTimes[1];
    }

    include_once("JaduAutoload.php");
    include_once("utilities/JaduAdminPageAccess.php");
    include_once('utilities/JaduAdminCurrentLogins.php');
    include_once("utilities/JaduModulePages.php");
    include_once('utilities/JaduModules.php');
    include_once("utilities/JaduAdminActivityLog.php");

    if (defined('USE_JADU_SESSION_HANDLER') && USE_JADU_SESSION_HANDLER == true) {
        require_once('utilities/JaduSessionHandler.php');
        $sessionHandler = new JaduSessionHandler();
    }

    $session = Jadu_Service_Container::getInstance()->getJaduSession('session', 'jadu_cc');
    
    // @todo remove and put into Service Container
    global $site;

    if((!isset($SECURE_JADU_PATH) || is_null($SECURE_JADU_PATH)) && defined('SECURE_JADU_PATH')) {
        $SECURE_JADU_PATH = SECURE_JADU_PATH;
    }

    //  if session variable for admin not already set, then set it
    if (!$session->get('adminID'.$site->id) || (!$session->get('adminID'.$site->id) || $session->get('adminID'.$site->id) < 0)) {
        if (defined('LDAP_CC_AUTH_ENABLED') && LDAP_CC_AUTH_ENABLED) {

            include_once('utilities/JaduLDAPSettings.php');

            $ldapSettings = getLiveLDAPSettings();
            $ldapSettings = $ldapSettings[0];
        }

        if (defined('LDAP_CC_AUTH_ENABLED') && LDAP_CC_AUTH_ENABLED && $ldapSettings->loginMode == LDAP_AUTH_MODE_IWA) {
            if ($_SERVER['AUTH_USER'] != '' && !$session->get('adminID'.$site->id)) {
                $username = mb_substr($_SERVER['AUTH_USER'], mb_strpos($_SERVER['AUTH_USER'], '\\'));
            }
            else {
                //login failed
                header('Location: ' . $adminService->getSignInURL());
                exit;
            }

            $admins = $adminService->getAllAdministrators(array("username" => $username));
            if(!empty($admins) && $admins[0]->id > 0) {
                $adminService->initSession($admins[0]->id);
            }
        }
        else {
            if ($_SERVER['REQUEST_URI'] != 'change_password.php') {
                header('X-Jadu-Login: true');
                if(Jadu_Service_Container::getInstance()->getRequest()->isAjax()) {
                    header("HTTP/1.0 401 Unauthorized");
                    exit();
                }
                header("Location: " . $adminService->getSignInURL() . "?destination=" . urlencode($_SERVER['REQUEST_URI']));
                exit();
            }
        }
    }

    if (defined('DISALLOW_CONCURRENT_LOGINS') && DISALLOW_CONCURRENT_LOGINS) {
        $lastLogin = getLastAdminLoginForAdmin($session->get('adminID'.$site->id));
        if (!$lastLogin || $lastLogin->id != $session->get('loginID' . $site->id)) {
            $session->set('adminID'.$site->id, 0);
            $session->set('loginID'.$site->id, 0);

            header('X-Jadu-Login: true');
            // refresh the page to start a new session
            header("Location: " . $adminService->getSignInURL() . "?concurrentLogin=true&destination=" . urlencode($_SERVER['REQUEST_URI']));
            exit;
        }
    }

    $admin = $adminService->getCurrentAdmin();
    $adminID = $admin->id;

    //if the admin has been deleted / cannot be found / there is a bad session
    if ($adminID < 1) {
        
        $session->set('adminID'.$site->id, '0');
        $session->set('loginID'.$site->id, '0');

        // refresh the page to start a new session
        header('X-Jadu-Login: true');
        header("Location: " . $adminService->getSignInURL() . "?destination=" . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }

    //Determine if this session_header was loaded as part of a who-is-online update
    if (!strpos($_SERVER['REQUEST_URI'], "who_is_online.php")) {
        //This is not a who-is-online update.
        $session->set('lastActionTimestamp', time());
    }

    $sessionLastActive = intval($session->get('lastActionTimestamp'));
    $sessionTimeout = intval(get_cfg_var("session.gc_maxlifetime"));
    $sessionExpires = $sessionLastActive + $sessionTimeout;

    //check that the admin has been active and that the session has not just been sustained by who_is_online
    if (time() > $sessionExpires) {
        session_destroy();
        header('X-Jadu-Login: true');
        header("Location: " . $adminService->getSignInURL() . "?destination=" . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }

    // check that the admins password has not expired
    if ($adminService->getCurrentAdmin()->isLDAPUser == ADMIN_NOT_LDAP_USER) {
        if ($adminService->getCurrentAdmin()->getDaysUntilPasswordChange() < 1 && mb_strpos($_SERVER['PHP_SELF'], 'change_password') === false) {
            header("Location: " . $adminService->getPasswordChangeURL());
            exit;
        }
    }

    $start = strpos($_SERVER['PHP_SELF'], '/jadu') + 5;
    $length = strpos($_SERVER['PHP_SELF'], '.php') - $start + 4;
    $cc_url = substr($_SERVER['PHP_SELF'], $start, $length);

    // check that  the admin has access to the current page (or the
    // parent of the current page if it has one)
    $modulePage = getModulePageFromURL($cc_url);
    $page_id = $modulePage->id;

    if ($modulePage->parent_id != -1 && $modulePage->isTab == 0) {
        $modulePage = getModulePage($modulePage->parent_id);
        $page_id = $modulePage->id;
    }

    $noAccess = true;
    if (!adminHasAccessToPage($adminID, $page_id)) {
        //if admin does not have access to this page, get any sub pages (if it's a parent), and use access from those.
        //(this is on the assumption that no parent page has permissions more sensitive than its child pages)
        $subPages = getAllModulePagesForModule($modulePage->module_id, '', $modulePage->id, false, 1);
        foreach ($subPages as $subPage) {
            if (adminHasAccessToPage($adminID, $subPage->id)) {
                $noAccess=false;
                break;
            }
        }
    }
    else {
        $noAccess = false;
    }

    if ($performRedirect && $modulePage->id > 0 && $noAccess && $modulePage->module_id != ADDITIONAL_PAGES_MODULE_ID) {
        header("Location: $SECURE_JADU_PATH/?permissionError");
        exit;
    }

    //  log details of viewing page into Database
    if ($page_id > 0 && $ADMIN_LOGGING) {
        newAdminActivityLog ($adminID, "/jadu".$cc_url, $session->get('loginID'.$site->id));
    }

    // if an admin clicks on a module, save it to the session
    if (isset($_GET['moduleID'])) {
        $session->set('currentModuleID', (int) $_GET['moduleID']);
    }

    if (isset($_SERVER['HTTP_REFERER']) && mb_strpos($_SERVER['HTTP_REFERER'], 'blogAdmin') !== false) {
        define('IN_BLOG_CONTROL_CENTRE', true);
    }

    preg_match('/(\/[^\/]+\/)?[^\/]+$/i', $_SERVER['PHP_SELF'], $matches);


    if (!defined('SAAS') || SAAS == false) {
        $adminPageAccessPermissions = getAdminPageAccess ($adminID, $modulePage->id);
        if ($adminPageAccessPermissions->id == -1) {
            //if no permission exists for this page, use any available child permissions (this is on the assumption that no parent page has permissions more sensitive than its child pages)
            $subPagesPermissions = getAllModulePagesForModule($modulePage->module_id, '', $modulePage->id, false, 1);
            foreach ($subPagesPermissions as $subPagePermissions) {
                $adminPageAccessPermissions = getAdminPageAccess ($adminID, $subPagePermissions->id);
                if ($adminPageAccessPermissions->id > -1) {
                    break;
                }
            }
        }
    }
    else if (defined('SAAS') && SAAS == true) {
        $adminPageAccessPermissions = new AdminPageAccess();
        $adminPageAccessPermissions->admin_id = $session->get('adminID');
        $adminPageAccessPermissions->createContent = $session->get('createPrivilege');
        $adminPageAccessPermissions->updateContent = $session->get('updatePrivilege');
        $adminPageAccessPermissions->deleteContent = $session->get('deletePrivilege');
    }
    unset($matches);
?>
