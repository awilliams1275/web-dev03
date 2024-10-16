<div class="toolbar">
    <a href="#" class="mobile-menu-button">Menu</a>
    <div class="nav-user">
<?php
    // Switch Control Center
    $mainSiteAdminID = ($adminService->getCurrentAdmin()->mainSiteAdminID == -1 && INSTALLATION_TYPE != GALAXY) ? $adminService->getCurrentAdminID() : $adminService->getCurrentAdmin()->mainSiteAdminID;
    $universalLogin = new JaduUniversalLogin();
    $availableGalaxiesSites = $universalLogin->getAdministratorsGalaxies($mainSiteAdminID);

    $passwordReset = false;
    if (mb_strpos($_SERVER['PHP_SELF'], 'change_password') !== FALSE && ($adminService->getCurrentAdmin()->getDaysUntilPasswordChange() < 0 || $adminService->getCurrentAdmin()->pass_last_modified <= 0)) {
        $passwordReset = true;
    }

    if (!$passwordReset) {
?>
    <div class="dropdown">
<?php
    if (sizeof($availableGalaxiesSites) < 1) {
?>
        <span class="site-switcher__label">
            <?php print encodeHtml(METADATA_GENERIC_NAME); ?>
        </span>
<?php
    }
    else {
?>
        <button class="dropdown-toggle">
            <?php print encodeHtml(METADATA_GENERIC_NAME); ?>
            <span class="caret"></span>
        </button>
        <ul class="dropdown__menu pull-right">
<?php
        // Switch Control Center
        $mainSiteAdminID = ($adminService->getCurrentAdmin()->mainSiteAdminID == -1 && INSTALLATION_TYPE != GALAXY) ? $adminService->getCurrentAdminID() : $adminService->getCurrentAdmin()->mainSiteAdminID;
        $universalLogin = new JaduUniversalLogin();
        $availableGalaxiesSites = $universalLogin->getAdministratorsGalaxies($mainSiteAdminID);

        if (!empty($availableGalaxiesSites)) {
?>
        <li class="dropdown__header">Switch Control Center</li>
<?php
            $switchType = '';
            // Main Site
            if ($site->isMainSite) {    
                $switchType .= 'M';
?>
            <li class="is-active"><a class="switchCms" href="#" onclick="return false;"><i class="icon-ok-sign"></i><?php print $site->siteUrl; ?></a></li>
<?php
            } 
            else {  
                $switchType .= 'G';
                $domain = getMainDomain();
                $mainSite = getJaduSiteByDomainName($domain);
?>
            <li><a class="switchCms 1" href="#" onclick="SwitchCC.switchTo('<?php print $domain; ?>', '<?php print $switchType; ?>M'); return false;"><i class="icon-circle-blank muted"></i><?php print $domain; ?></a></li>
<?php       
            }
            $hash = $universalLogin->createUniverseLoginHash($admin);
            
            if (count($availableGalaxiesSites) > 10) {
?>
            <li><select class="select" id="galaxyCCSwitch" onchange="if (this.value.length > 0) { SwitchCC.switchTo(this.value, '<?php print $switchType; ?>G'); } return false;">
            <option value="">Choose from...</option>
<?php
                // Loop Galaxies
                $i = 0;
                foreach ($availableGalaxiesSites as $availableSite) {
                    
                    $tempURL = $availableSite->getDefaultURL();
                    if ($site->siteUrl == $tempURL) {
                        print '<option value="">'.$site->siteUrl.' <em>(currently signed in)</em></option>';
                    } 
                    else {
                        print '<option value="'.$tempURL.'">'.$tempURL.'</option>';
                    }
                $i++;
                }
?>
        </select></li>
<?php
            }
            else {
                // Loop Galaxies
                $i = 0;
                foreach ($availableGalaxiesSites as $availableSite) {
                    
                    $tempURL = $availableSite->getDefaultURL();
                    if ($site->siteUrl == $tempURL) {
                        print '<li><a class="switchCms is-active" href="#" onclick="return false;"><i class="icon-ok-sign"></i> ' . $site->siteUrl . '</a></li>';
                    } 
                    else {
?>
                    <li><a  class="switchCms" href="#" onclick="SwitchCC.switchTo('<?php print $tempURL; ?>', '<?php print $switchType; ?>G'); return false;"><i class="icon-circle-blank muted"></i><?php print $tempURL; ?></a></li>
<?php
                    }
                    $i++;
                }
            }
        }
    }
?>
    </div>
    <div class="dropdown">
        <button class="dropdown-toggle">
            <?php print encodeHtml($adminService->getCurrentAdmin()->name); ?>
            <?php if ($numberOfTasks >= 0) { ?>
                (<?php print $numberOfTasks; ?>)
            <?php } ?>
            <span class="caret"></span>
        </button>
        <ul class="dropdown__menu pull-right">
<?php
    if (!defined('GALAXIES_STANDALONE_INSTALL') || (defined('GALAXIES_STANDALONE_INSTALL') && GALAXIES_STANDALONE_INSTALL == false)) {
?>
        <li class="tasks"><a href="<?php print $SECURE_JADU_PATH; ?>/tasklist/to_do.php?moduleID=4"><span class="badge pull-right"><?php print (int) $numberOfTasks; ?></span>My Tasks</a></li>
<?php
    }
    if ($adminService->getCurrentAdmin()->isLDAPUser == ADMIN_NOT_LDAP_USER) {
?>
            <li class="password"><a href="<?php print $SECURE_JADU_PATH; ?>/change_password.php?moduleID=4">Change Password</a></li>
<?php
    }
?>
            <li class="password"><a href="<?php print $SECURE_JADU_PATH; ?>/settings">Settings</a></li>
<?php
    if (!defined('GALAXIES_STANDALONE_INSTALL') || (defined('GALAXIES_STANDALONE_INSTALL') && GALAXIES_STANDALONE_INSTALL == false)) {

        $additionalPage = getModulePageFromName('Dashboard');
        if (adminHasAccessToPage($adminService->getCurrentAdminID(), $additionalPage->id)) {
?>      
        <li class="dashboard"><a href="<?php print $SECURE_JADU_PATH; ?>/tasklist/console.php?moduleID=4">Dashboard</a></li>
<?php
        }
    }

    $nonce = Jadu_Service_Container::getInstance()->getJaduSessionNonce()->generate();
?>
    <li class="logout"><a href="<?php print $SECURE_JADU_PATH; ?>/logout<?php print '?token=' . $nonce; ?>" onclick="return confirmLogout();">Sign Out</a></li>
        </ul>
    </div>
<?php
    }
?>
</div>

<?php
$config = Jadu_Service_Container::getInstance()->getConfig('notifications')->toArray();
print partial('@assets/components/notifications.html.twig', array('feeds' => Jadu_Service_Container::getInstance()->getNotification()->parseMultipleAndInsert($config['notifications'])->getMyUnreadNotifications()));
?>
</div>
