<nav class="breadcrumb">
<?php
	/**
	* Show the module the user is in and the current page they are on.
	*
	* $cc_url: 
	*	for the URL 'http://www.jadu.co.uk/jadu/websections/websection_sections_list.php?moduleID=1',
	*	$cc_url will equal '/websections/websection_sections_list.php'.
	*/
	
	$modulePageParent = new ModulePage();
	
	if ($cc_url == '/utils/track_changes.php' && isset($url)) {
		$modulePageURL = str_replace(SECURE_JADU_PATH, '', $url);
		$modulePageURL = strpos($modulePageURL, '?') !== false ? substr($modulePageURL, 0, strpos($modulePageURL, '?')) : $modulePageURL;
		$modulePage = getModulePageFromURL($modulePageURL);
		
		if ($modulePage->parent_id > -1) {
			$modulePageParent = getModulePage($modulePage->parent_id);
		}
	} else {
		$modulePage = getModulePageFromURL($cc_url);
		if ($modulePage->parent_id > -1) {
			$modulePageParent = getModulePage($modulePage->parent_id);
		}
	}

	if ($modulePage->parent_id == -1 && $modulePage->module_id == -1) {
		if (mb_strpos($modulePage->name, '/')) {
			$modulePageSplit = explode('/', $modulePage->name);
			print encodeHtml($modulePageSplit[0]).' <span> / '.encodeHtml($modulePageSplit[1]).'</span>';	
		} else if ($cc_url == '/websections/supplement_details.php' || $cc_url == '/websections/supplement_list.php') {
			print 'Publishing <span>';
			if (isset($contentType)) {
				switch ($contentType) {
					case 'document':
						print ' / <a href="' . SECURE_JADU_PATH . '/websections/websection_sections_list.php" title="Document Pages">Document Pages</a>';
						break;
						
					case 'homepage':
						print ' / <a href="' . SECURE_JADU_PATH . '/websections/homepages_list.php" title="Homepages">Homepages</a>';
						break;
						
					case 'faq':
						print ' / <a href="' . SECURE_JADU_PATH . '/websections/websection_faq_historic.php" title="FAQs">FAQs</a>';
						break;
				}
			}
		} else { 
			print encodeHtml($modulePage->name);
		}
	} else {
		if (empty($moduleName)) {
			$module = getModule($modulePage->module_id);
			$moduleName = $module->name;
		}
		
		print strip_tags($moduleName);

		if (!empty($modulePageParent->name)) {
			if (isset($_GET['moduleID'])) {
				print ' / <a href="' . SECURE_JADU_PATH . $modulePageParent->page_url.'?moduleID=' . (int) $_GET['moduleID'] . '" title="' . encodeHtml($modulePageParent->name) . '">';
			} else {
				print ' / <a href="' . SECURE_JADU_PATH . $modulePageParent->page_url . '" title="' . encodeHtml($modulePageParent->name) . '">';
			}
			print encodeHtml($modulePageParent->name).'</a>';
		}

		if (!empty($modulePage->name)) {
			if ($cc_url == '/utils/track_changes.php' && isset($url)) {
				print '<a href="' . $url . '">' . encodeHtml($modulePage->name) . '</a>';
			}
		}

		if (!empty($modulePage->name)) {
			print '</span>';
		}
	}
	
	if ($cc_url == '/utils/track_changes.php') {
		print ' <span> / Track Changes</span>';
	}

	if (isset($showSearchInBreadcrumb) && $showSearchInBreadcrumb) {
?>
		 <span id="searchQueryText"></span>
<?php
	}
?>
</nav>