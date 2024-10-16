<?php
if (!isset($_SESSION['saasID'])) {
    include_once('JaduConstantsFunctions.php');
    require_once('JaduImages.php');
    $passwordReset = false;    
    if (mb_strpos($_SERVER['PHP_SELF'], 'change_password') !== FALSE && ($adminService->getCurrentAdmin()->getDaysUntilPasswordChange() < 0 || $adminService->getCurrentAdmin()->pass_last_modified <= 0)) {
        $passwordReset = true;
    }
?>
<nav class="nav-main"><div class="nav-primary"><!--
    --><a href="<?php print $SECURE_JADU_PATH; ?>/search" class="jadu-branding"><!--
    --><span class="jadu-logomark"></span><!--
    --><span class="jadu-wordmark">Jadu</span></a><!--
    --><ul class="nav-items">
<?php
    $accessiblePageIDs = getAllAccessiblePageIDsForAdmin($adminService->getCurrentAdminID());
    if (!empty($accessiblePageIDs) && !$passwordReset) {

        $secondaryNavList = array();

        // loop through each module
        foreach ($allModules as $module) {
            // get all pages in module
            $modulePages = getAllModulePagesForModule($module->id);

            // by default don't show the module
            $showModule = false;

            // loop through each module page - if the admin has access then show the module link
            foreach ($modulePages as $modulePage) {
                if (in_array($modulePage->id, $accessiblePageIDs)) {
                    $showModule = true;
                    break;
                }
            }
            if ($showModule) {
?>
            <li class="nav-item icon-bodge-<?php print $module->id ?>">
                <a href="#<?php print $module->id ?>" class="nav-link"><i class="icon-chevron-sign-right nav-link__icon"></i>
                    <?php print $module->name; ?>
                </a>
            </li>
<?php
                $i = 0;

                $secondaryNavItem = '<ul class="nav-list" data-nav="#' . $module->id . '">
                            <li class="nav-item nav-item--header">' . $module->name . '</li>';

                foreach ($modulePages as $modulePage) {

                    if (in_array($modulePage->id, $accessiblePageIDs) && $modulePage->parent_id == -1) {

                        if ((!isset($previousModulePage) || $modulePage->pageGroup != $previousModulePage->pageGroup) && $i != 0) {
                            $secondaryNavItem .= '<li class="nav-item nav-item__divider"><hr class="nav-divider"></li>';
                        }
                        
                        $moduleUrl = $SECURE_JADU_PATH . $modulePage->page_url . (mb_strpos($modulePage->page_url,'?') === false ? '?' : '&amp;') . 'moduleID=' . (int) $module->id;

                        $secondaryNavItem .= '<li class="nav-item">
                                <a href="' . $moduleUrl . '" class="nav-link">
                                    ' . htmlentities($modulePage->name) . '
                                </a>
                            </li>';
                    
                        $previousModulePage = $modulePage;
                        $secondaryNav[] = $secondaryNavItem;
                        
                        $i++;
                    }
                }
                        
                $secondaryNavItem .= '</ul>';

                $secondaryNavList[] = $secondaryNavItem;
            }
        }
    }
?>
        </ul>
    </div><!--

 --><div class="nav-secondary">
        <a href="#close" class="nav-close" data-nav-action="close"><i class="icon-remove"></i></a>

        <!-- <form class="nav-search">
            <input type="search" placeholder="Search" class="nav-search__field">
            <button class="nav-search__submit"><i class="icon-search"></i></button>
        </form> -->
<?php
    if (isset($secondaryNavList)) {
        foreach ($secondaryNavList as $secondaryNavListItem) {
            print $secondaryNavListItem;
        }
    }
?>
    </div>
</nav>
<?php
}
else {
    print '<h2 class="saas_header"></h2><span id="st_msg"></span>';
}