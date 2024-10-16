<script type="text/javascript">		
	Event.observe(window, 'load', function() { 
		// Initialise who_is_online
		var whoIsOnline = new WhoIsOnline({ 
			checkFrequency: '<?php print defined('CHECK_WHO_IS_ONLINE_FREQUENCY') ? CHECK_WHO_IS_ONLINE_FREQUENCY : 600; ?>'
		});
		//attach events to the names found by the loading inside this page (avoid running 'load' immediately for every page load!)
		whoIsOnline.attachEvents($('onlineAdminsStartActive').value);

		$('searchOnlineAdmins').observe('keyup', function (e) {
			whoIsOnline.filterResults(e);
		});
		
	});
</script>

<?php
	if (adminHasAccessToPage($adminService->getCurrentAdminID(), getModulePageFromURL("/utils/admin_stats.php")->id)) {
		print '<input type="hidden" id="canViewStats" value="1" />';
	}
	if ($adminService->getCurrentAdmin()->content_view != RESTRICT_TO_OWN_CONTENT) {
		print '<input type="hidden" id="canViewContent" value="1" />';
	}
	
	$onlineAdmins = getAllAdministratorsFromList(getDistinctAdminIDsSinceActivityTimestamp((intval(floor((time()/10)))*10)-1200));
	if (sizeof($onlineAdmins) === 1 && $onlineAdmins[0]->id == $adminService->getCurrentAdminID() || sizeof($onlineAdmins) == 0) {
		$showOnlineAdmins = false;
	}
	else {
		$showOnlineAdmins = true;
	}
?>
<input type="hidden" id="onlineAdminsStartActive" value="<?php print ($showOnlineAdmins) ? '1' : '0'; ?>" />
	
<div id="collaborationWindow" style="display: none;">
	<h3>Online now</h3>
	<ul id="who_is_online">

<?php
	foreach ($onlineAdmins as $onlineAdmin) {
		if ($onlineAdmin->id !== $adminService->getCurrentAdminID()) {
?>
	<li class="onlineAdmin" id="onlineAdmin<?php print $onlineAdmin->id; ?>">
			<a href="#" onclick="return false;"><?php print $onlineAdmin->name; ?></a>
		
			<ul class="collabTasks">
<?php
			if (adminHasAccessToPage($adminService->getCurrentAdminID(), getModulePageFromURL("/utils/admin_stats.php")->id)) {
?>
				<li><a title="View Activity" class="activity" href="<?php print SECURE_JADU_PATH; ?>/direct.php?action=who_is_online_popup_stats&to=<?php print urlencode('/utils/admin_stats.php?statAdminID=' . intval($onlineAdmin->id) . '&recent=24&searchRecent=Go'); ?>"><span>View Activity</span></a></li>
<?php
			}
			if ($adminService->getCurrentAdmin()->content_view != RESTRICT_TO_OWN_CONTENT) {
?>
				<li><a title="View Content" class="content" href="<?php print SECURE_JADU_PATH; ?>/direct.php?action=who_is_online_popup_content&to=<?php print urlencode('/search/search_results.php?q=&owner=' . intval($onlineAdmin->id)); ?>"><span>View content</span></a></li>
<?php
			}
?>	
				<li><a title="Email <?php print encodeHtml($onlineAdmin->name); ?>"  class="email" href="mailto:<?php print $onlineAdmin->email; ?>" onclick="new Ajax.Request(SECURE_JADU_PATH + '/direct.php?action=who_is_online_popup_mailto&to=' + encodeURIComponent('mailto:<?php print $onlineAdmin->email; ?>')); return true;"><span>Email <?php print encodeHtml($onlineAdmin->name); ?></span></a></li>
			</ul>
		
	</li>
<?php
		}
		else {
			if (count($onlineAdmins) === 1) {
				print '<li class="justYou">You\'re the only one</li>';
			}
		}
	}
	if (count($onlineAdmins) === 0) {
		print '<li class="justYou">You\'re the only one</li>';
	}
?>
	</ul>
	<div id="searchOnlineAdminsContainer" <?php print ($showOnlineAdmins) ? '' : 'style="display: none;"'; ?>>
		<input type="text" class="input" id="searchOnlineAdmins" value="" />
	</div>
</div>