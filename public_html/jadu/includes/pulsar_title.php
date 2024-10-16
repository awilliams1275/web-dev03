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
	}
	else {
		$modulePage = getModulePageFromURL($cc_url);
		if ($modulePage->parent_id > -1) {
			$modulePageParent = getModulePage($modulePage->parent_id);
		}
	}

	$mainTitle = '';

	if ($modulePage->parent_id == -1 && $modulePage->module_id == -1) {
		if (mb_strpos($modulePage->name, '/')) {
			$modulePageSplit = explode('/', $modulePage->name);
			$mainTitle = $modulePageSplit[0];
		}
		else if ($cc_url == '/websections/supplement_details.php' || $cc_url == '/websections/supplement_list.php') {
			if (isset($contentType)) {
				switch ($contentType) {
					case 'document':
						$mainTitle = 'Page Details';
						break;
					case 'homepage':
						$mainTitle = 'Homepages';
						break;
					case 'faq':
						$mainTitle = 'FAQs';
						break;
				}
			}
		}
	} 

	if (!empty($modulePage->name)) {
        if (mb_strpos($modulePage->name, '/')) {
            $modulePageSplit = explode('/', $modulePage->name);
            $mainTitle = $modulePageSplit[0];

            if (trim($mainTitle) == 'Site On') {
        		$mainTitle .= ' / ' . encodeHtml($modulePageSplit[1]);
            }
        } 
        else {
		  $mainTitle = $modulePage->name;
        }
	} else if (!empty($modulePageParent->name)) {
		$mainTitle = $modulePageParent->name;
	}
?>

<h1 class="main-title"><?php print encodeHtml($mainTitle); ?>
</h1>
<div id="autosave-spinner"></div>
<small id="last-autosave-message"></small>
