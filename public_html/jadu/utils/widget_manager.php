<?php
	require_once("../includes/session_header.php");
	require_once("JaduCategories.php");
	require_once("websections/JaduHomepageWidgets.php");
	require_once("websections/JaduHomepageWidgetsToSites.php");
	require_once("../includes/global.php");
	require_once("../includes/page_numbers.php");
	
	
	//variables for the page numbers
	if (isset($_GET['records_per_page'])) {
		$recordsPerPage = $_GET['records_per_page'];
	}
	else {
		$recordsPerPage = 10;
	}
	
	$searchQuery = '';
	$searchScope = -1;
	$searchVisibility = -1;
	$searchEnabled = false;
	
	if (isset($_GET['query'])) {
		$searchQuery = trim($_GET['query']);
	}
	
	if (isset($_GET['scope'])) {
		$searchScope = (int)$_GET['scope'];
	}
	
	if (isset($_GET['visibility'])) {
		$searchVisibility = (int)$_GET['visibility'];
	}
	
	if ($searchQuery != '' || $searchScope != -1 || $searchVisibility != -1) {
		$searchEnabled = true;
	}
	
	if (isset($_GET['viewSearch'])) {
		$viewSearch = true;
	}
	
	if (isset($_GET['deleteIDs'])) {
		if ($adminPageAccessPermissions->deleteContent) {
			foreach ($_GET['deleteIDs'] as $id) {
				$widget = new HomepageWidget();
				$widget->setInternalID($id);
				$widget->delete();
			}
			publishAllWidgetsContentJs();			
			$statusMessage = 'Selected Widgets deleted';
		}
		else {
			header("Location: ".SECURE_JADU_PATH.'/?permissionError=true');
			exit;
		}
	}
	
	if (isset($_GET['setVisibility']) && isset($_GET['internalID'])) {
		if ($adminPageAccessPermissions->updateContent) {
				$widget = getHomepageWidget($_GET['internalID']);
				if (($_GET['setVisibility'] === '1') != $widget->isVisible()) {
					$widget->setVisibility($_GET['setVisibility'] === '1');
					// need to redirect as constant has been changed
					header('Location: ./widget_manager.php?widget_saved');
					exit;
				}
		}
	}
	
	if (isset($_GET['widget_deleted'])) {
		$statusMessage = 'Widget deleted';
	}
	else if (isset($_GET['widget_saved'])) {
		$statusMessage = 'Widget saved';
	}
	
	$allWidgets = getAllHomepageWidgetsForSite();
	
	$multibyteStringSearch = function_exists('mb_stripos');
	foreach ($allWidgets as $key => $widget) {
		if ($searchVisibility > -1 && $widget->isVisible() != $searchVisibility) {
			unset($allWidgets[$key]);
			continue;
		}
		
		if ($searchScope > -1 && $widget->scope != $searchScope) {
			unset($allWidgets[$key]);
			continue;
		}
		
		if ($searchQuery != '') {
			$widgetSearchText = $widget->title . ' ' . $widget->description . ' ' .$widget->contentCode . ' '. $widget->contentCodeBehind;
			if (($multibyteStringSearch && mb_stripos($widgetSearchText, $searchQuery) === false) || (!$multibyteStringSearch && stripos($widgetSearchText, $searchQuery) === false)) {
				unset($allWidgets[$key]);
				continue;
			}
		}
	}
	
	$recordCount = count($allWidgets);
	
	$pageCount = ceil($recordCount / $recordsPerPage);
	
	$currentPage = 1;
	
	if (isset($_GET['page'])) {
		$currentPage = intval($_GET['page']);
	}
	
	if ($currentPage > $pageCount) {
		$currentPage = $pageCount;
	}
	
	if ($currentPage < 1) {
		$currentPage = 1;
	}
	
	$start = ($currentPage - 1) * $recordsPerPage;
	
	$allWidgets = array_slice($allWidgets, $start, $recordsPerPage);
	
	
	
	include("../includes/head.php");
?>
	<!-- ################### CC Content ################### -->
	<script type="text/javascript">
	
	function setVisibility (sel, internalID)
	{
		location = "./widget_manager.php?internalID=" + internalID + "&setVisibility=" + sel.options[sel.selectedIndex].value + "&viewSearch=";
	}
	
	function viewSearch()
	{
		if (document.getElementById('searchArea').style.display == "none") {
			document.getElementById('searchArea').style.display = "";
			document.queryForm.viewSearch.value = "true";
			document.getElementById('query').focus();
		}
		else {
			document.getElementById('searchArea').style.display = "none";
			document.queryForm.viewSearch.value = "false";
		}
		
		return false;
	}
	
	</script>
	
<?php
	$tabPages = array( 'Manage' => '/utils/widget_manager.php',
						'Styles' => '/utils/widget_styles.php'
	);
	
	include('../includes/pulsar_nav_inline.php');
?>
	
	<p class="expand_search_find"><a href="#" title="Filter sites" onclick="return viewSearch();">Find a Widget</a></p>
	<form action="./widget_manager.php" method="get" name="queryForm" id="queryForm">
		<input type="hidden" name="viewSearch" value="true" />
		<div id="searchArea" name="searchArea" style="display: <?php if (isset($viewSearch) && $viewSearch == false) { ?>none<?php } ?>;">
			<table class="list_table">
				<tr>
					<th><span>Keyword</span> Title, Description, Code</th>
					<th><span>Filter</span></th>
					<th><span>Visibility</span></th>
				</tr>
				<tr class="expand_search">
				
					<td class="table_finish_curve">
						<input type="text" id="query" value="<?php print encodeHtml($searchQuery); ?>" name="query">
						<input type="submit" class="btn interimButton" name="submitQuery" value="Go">
					</td>
					<td>
						<span>
						<select name="scope">
							<option value="-1"<?php if ($searchScope == -1) print ' selected="selected"'; ?>>Any</option>					
							<option value="<?php print HomepageWidget::WIDGET_SCOPE_ALL_SITES; ?>"<?php if ($searchScope == HomepageWidget::WIDGET_SCOPE_ALL_SITES) print ' selected="selected"'; ?>>All sites</option>					
							<option value="<?php print HomepageWidget::WIDGET_SCOPE_THIS_SITE; ?>"<?php if ($searchScope == HomepageWidget::WIDGET_SCOPE_THIS_SITE) print ' selected="selected"'; ?>>This site only</option>
							<option value="<?php print HomepageWidget::WIDGET_SCOPE_GALAXY_ONLY; ?>"<?php if ($searchScope == HomepageWidget::WIDGET_SCOPE_GALAXY_ONLY) print ' selected="selected"'; ?>>Galaxies only</option>
							<option value="<?php print HomepageWidget::WIDGET_SCOPE_ENTERPRISE_SITES; ?>"<?php if ($searchScope == HomepageWidget::WIDGET_SCOPE_ENTERPRISE_SITES) print ' selected="selected"'; ?>>Enterprise sites only</option>
						</select>
						<input type="submit" class="btn interimButton" name="submitScope" value="Go" />&nbsp;</span>
					</td>
					<td>
						<span>
						<select name="visibility">
							<option value="-1" <?php if ($searchVisibility == -1) { ?> selected="selected"<?php } ?>>Any</option>
							<option value="1" <?php if ($searchVisibility == 1) { ?> selected="selected"<?php } ?>>Visible</option>
							<option value="0" <?php if ($searchVisibility == 0) { ?> selected="selected"<?php } ?>>Not visible</option>
						</select>
						<input type="submit" class="btn interimButton" name="submitVisibility" value="Go" />&nbsp;</span>
					</td>
				</tr>
			</table>
		</div>
	</form>
	<br />
<?php
	if (empty($allWidgets) && !$searchEnabled) {
?>
	<div class="not_yet"><h3>You have not yet added any widgets - <a title="Upload a widget" href="widget_details.php?internalWidgetID=-1">Create one now!</a></h3></div>
<?php
	}
	else if (empty($allWidgets)) {
?>
		<div class="not_yet"><h3>Your search returned no results - <a title="Upload a widget" href="./widget_manager.php">Show all</a> / <a title="Upload a widget" href="widget_details.php?internalWidgetID=-1">Create one now!</a></h3></div>
<?php
	}
	else {
?>
	<form name="deleteForm" action="widget_manager.php" method="get" id="deleteForm">
		<input type="hidden" name="currentPage" value="<?php print $currentPage; ?>" />
		<table class="list_table">
			<tr>
				<th><span>Widget</span> name</th>
				<th><span>Last</span> modified</th>
				<th><span>Uploaded</span> by</th>
				<th><span>Visible</span></th>
<?php
            if ($adminPageAccessPermissions->deleteContent) {
?>
				<th class="empty"></th>
<?php
            }
?>
			</tr>
<?php
		foreach ($allWidgets as $widget) {
			$siteUrl = '';
			if (INSTALLATION_TYPE == GALAXY && $widget->mainSite) {
				// Get the Administrator from the main CMS database for enterprise widgets
				$siteUrl = MAIN_DOMAIN;
			}
			
			$creator = $adminService->getAdministrator($widget->adminID, $siteUrl);
			if ($creator->name == '' && $widget->standard == '1') {
				// Default standard widgets to being created by Jadu
				$creator->name = 'Jadu';
			}
			
?>
			<tr>
<?php
            if ($adminPageAccessPermissions->updateContent) {
?>
				<td class="generic_row_link"><a href="widget_details.php?internalWidgetID=<?php print $widget->getInternalID(); ?>"><?php print encodeHtml($widget->title); ?></a></td>
<?php
            }
            else {
?>
                <td class="generic_row"><?php print encodeHtml($widget->title); ?></td>
<?php
            }
?>
				<td class="generic_row"><?php print formatDateTime(FORMAT_DATE_SHORT, $widget->dateCreated); ?></td>
				<td class="generic_row"><?php print defined('MAIN_DOMAIN') && $siteUrl == MAIN_DOMAIN ? '<em>' . encodeHtml($creator->name) . '</em>' : encodeHtml($creator->name); ?></td>
				<td class="generic_row">
				
					<select name="visible" id="visible" onchange="setVisibility(this, '<?php print $widget->getInternalID(); ?>');">
						<option value="1"<?php if ($widget->isVisible()) { ?> selected="selected"<?php } ?>>Yes</option>
						<option value="0"<?php if (!$widget->isVisible()) { ?> selected="selected"<?php } ?>>No</option>
					</select>
<?php
			if ($widget->isVisible()) {
?>
					<img src="../images/green.png" width="9" height="9" />
<?php
			}
			else {
?>
					<img src="../images/red.png" width="9" height="9" />
<?php
			}
?>
				</td>

<?php
            if ($adminPageAccessPermissions->deleteContent) {
?>
				<td class="generic_row_end">
<?php
			    if ($widget->canBeDeletedOnSite() && $adminPageAccessPermissions->deleteContent) {
?>
					<span>
						<input class="checkbox" type="checkbox" name="deleteIDs[]" value="<?php print $widget->getInternalID();?>" />
					</span>
<?php
			    }
?>
				</td>
<?php
            }
?>
			</tr>
<?php
		}
?>
			<tr class="list_table_finish">
				<td class="table_finish_curve" colspan="4">
<?php
		if ($adminPageAccessPermissions->createContent) {
?>
				    <input type="button" class="btn interimButton" name="newWidget" value="Upload New widget" onclick="window.location='widget_details.php';">
<?php
		}
?>
				</td>
<?php
		if ($adminPageAccessPermissions->deleteContent) {
?>
				<td>
					<input type="submit" class="btn interimButton" name="deleteWidgets" value="Delete" onclick="return confirmDeleteSelected('deleteIDs[]');" />
				</td>
<?php
		}
?>
			</tr>
		</table>
	</form>
<?php
		displayListPageNumbers(SECURE_JADU_PATH.'/utils/widget_manager.php', $pageCount, $currentPage, $_GET, $recordsPerPage);

	}
?>
		<!-- ################################################ -->
<?php include("../includes/footer.php"); ?>
