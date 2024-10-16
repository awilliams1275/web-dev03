<?php
	require_once("../includes/session_header.php");
	require_once("websections/JaduHomepageWidgets.php");
	require_once("websections/JaduHomepageWidgetsToSites.php");
	require_once("JaduCategories.php");
	require_once("../includes/global.php");
	
	if (isset($_POST['returnMenu'])) {
		header('Location:widget_manager.php');
		exit;
	}
	
	if (isset($_GET['internalWidgetID']) && $_GET['internalWidgetID'] !== '-1') {
		$widget = getHomepageWidget($_GET['internalWidgetID']);
	}
	else {
		$widget = new HomepageWidget();		
	}

	$widgetIsEditable = true;
	if (INSTALLATION_TYPE == GALAXY && $widget->id > -1 && $widget->mainSite) {
		$widgetIsEditable = false;
	}
	
	if (isset($_POST['delete']) && $_POST['delete'] == 1) {
		$deleted = false;
		if (isset($_GET['internalWidgetID']) && $widgetIsEditable) {
			if ($adminPageAccessPermissions->deleteContent) {			
				$widget = getHomepageWidget($_GET['internalWidgetID']);
				$widget->delete();
			
				publishAllWidgetsContentJs();
				$deleted = true;
			}
		}
		
		if ($deleted) {
			header('Location: ./widget_manager.php?widget_deleted');
			exit;
		}
		else {
			// delete failed
		}
	}
	
	$error_array = array();
	
	if (isset($_POST['duplicateWidget']) && $_POST['duplicateWidget'] == 1 && $widget->id > -1) {
		
		$widget->setInternalID($_GET['internalWidgetID']);
		$duplicatedHomepageWidgetID = $widget->duplicate();
		
		if ($duplicatedHomepageWidgetID > -1) {
			
			$homepageWidget = new HomepageWidget();
			$homepageWidget->id = $duplicatedHomepageWidgetID;
			$homepageWidget->setMainSite();
			
			header('Location:widget_details.php?internalWidgetID=' . $homepageWidget->getInternalID() . '&statusMessage=' . urlencode('Duplicated Widget'));
		}
		else {
			header('Location:widget_manager.php');
		}
		exit;
	}
	else if (isset($_POST['uploadWidget']) && $widgetIsEditable) {
		$widget->title = $_POST['title'];
		$widget->defaultWidthPercentage = $_POST['defaultWidthPercentage'];
		$widget->description = $_POST['description'];
		if (isset($_POST['requiresCategory']) && is_array($_POST['requiresCategory'])) {
			$widget->requiresCategory = array_sum($_POST['requiresCategory']);
		}
		else {
			$widget->requiresCategory = 0;
		}
		$widget->memberOnly = $_POST['memberOnly'];
		$widget->scope = $_POST['scope'];
		$widget->adminID = $adminService->getCurrentAdminID();
		$widget->live = 0;
		$widget->setMainSite();

		if ($_POST['title'] == '') {
			$error_array['title'] = true;
		}
		if ($_POST['description'] == '') {
			$error_array['description'] = true;
		}
		if ($_POST['defaultWidthPercentage'] < 5) {
			$error_array['defaultWidthPercentage'] = true;
		}
		if (isset($_POST['contentJs']) && $_POST['contentJs'] != '') {
			if (preg_match('/<script.*/i', $_POST['contentJs']) == true) {
				$error_array['contentJs'] = true;
			}
		}

                if (isset($_POST['contentCode'])) {
                        $widget->contentCode = $_POST['contentCode'];
                }
                if (isset($_POST['contentCodeBehind'])) {
                        $widget->contentCodeBehind = $_POST['contentCodeBehind'];
                }
                if (isset($_POST['contentJs'])) {
                        $widget->contentJs = $_POST['contentJs'];
                }
                if (isset($_POST['settingsCode'])) {
                        $widget->settingsCode = $_POST['settingsCode'];
                }
                if (isset($_POST['jsCode'])) {
                        $widget->jsCode = $_POST['jsCode'];
                }
	
		if (empty($error_array)) {
			// Check the extension if a file has been uploaded
			if ($_POST['widgetContentType'][0] == 'file' && isset($_FILES['browseFile']) && $_FILES['browseFile']['name'] != '') {
				if ($_FILES['browseFile']['type'] != 'application/zip' && $_FILES['browseFile']['type'] != 'application/x-zip' && $_FILES['browseFile']['type'] != 'application/x-zip-compressed' && $_FILES['browseFile']['type'] != 'application/octet-stream') {
					$error_array['extension'] = true;
				}
				$destinationFile = HOME_DIR . 'var/tmp/' . md5($_FILES['browseFile']['tmp_name']);
				if (is_uploaded_file($_FILES['browseFile']['tmp_name']) && empty($error_array) && @move_uploaded_file($_FILES['browseFile']['tmp_name'], $destinationFile) && file_exists($destinationFile)) {
					// upload the import zip file
					$zip = zip_open($destinationFile);
					if ($zip) {		
						while ($zip_entry = zip_read($zip)) {
							$filename = zip_entry_name($zip_entry);
							// PHP widget: front-end markup
							if (basename($filename) == 'public.php' && SCRIPTING_LANGUAGE == PHP) {
								if (zip_entry_open($zip, $zip_entry, 'r')) {
									$widget->contentCode = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
									zip_entry_close($zip_entry);
								}
							}
							// PHP widget: control center settings (optional)
							if (basename($filename) == 'secure.php') {
								if (zip_entry_open($zip, $zip_entry, 'r')) {
									$widget->settingsCode = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
									zip_entry_close($zip_entry);
								}
							}
							// PHP widget: control center javascript (optional)
							if (basename($filename) == 'secure.js') {
								if (zip_entry_open($zip, $zip_entry, 'r')) {
									$widget->jsCode = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
									zip_entry_close($zip_entry);
								}
							}
							// PHP widget: front-end javascript (optional)
							if (basename($filename) == 'public.js') {
								if (zip_entry_open($zip, $zip_entry, 'r')) {
									$widget->contentJs = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
									zip_entry_close($zip_entry);
								}
							}
							
							// ASP.net widget: front-end markup
							if (basename($filename) == 'public.ascx' && SCRIPTING_LANGUAGE == DOT_NET) {
								if (zip_entry_open($zip, $zip_entry, 'r')) {
									$widget->contentCode = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
									zip_entry_close($zip_entry);
								}
							}
							// ASP.net widget: front-end code-behind
							if (basename($filename) == 'public.ascx.cs') {
								if (zip_entry_open($zip, $zip_entry, 'r')) {
									$widget->contentCodeBehind = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
									zip_entry_close($zip_entry);
								}
							}
						}
						zip_close($zip);
					}
					else {
						$error_array['upload'] = true;
					}
					@unlink($destinationFile);
				}
			}
			
			if ($widget->id == -1 || $widget->id == '') {
				$widget->insert();
			}
			else {
				$widget->update();
			}
			
			publishAllWidgetsContentJs();
			header('Location: ./widget_details.php?saved&internalWidgetID='.$widget->getInternalID());
		}
	}
	
	if (isset($_POST['uploadWidget']) && ($_POST['visible'] === '1') != $widget->isVisible()) {
		$widget->setVisibility($_POST['visible'] === '1');
		// need to redirect as constant has been changed
		header('Location: ./widget_details.php?saved&internalWidgetID='.$widget->getInternalID());
		exit;
	}
		
	if (isset($_GET['saved'])) {
			$statusMessage = 'Widget saved successfully';
	}
	
	if ($widget->id == '') {
		$widget->id = -1;
	}
	
    //To create the Numbered steps
    $stepCounter = 1;

	addJavascript('javascript/lightbox.js');
	addJavascript('utils/javascript/widget_details.js');

	include("../includes/head.php");
	
    if (isset($_REQUEST['widgetID']) && $_REQUEST['widgetID'] > 0 && !$adminPageAccessPermissions->updateContent) {
?>
    <div class="not_yet"><h3>You do not have permission to modify this widget.</h3></div>
<?php
    }
    else if (isset($_REQUEST['widgetID']) && $_REQUEST['widgetID'] == -1 && !$adminPageAccessPermissions->createContent) {
?>
    <div class="not_yet"><h3>You do not have permission to create a new widget.</h3></div>
<?php
    }
    else {
?>
	<!-- ################### CC Content ################### -->

	<div class="nav-inline">
	    <ul class="nav-inline__list">
			<li class="nav-inline__item<?php if (mb_strpos($_SERVER['PHP_SELF'], 'widget_manager.php') !== false || mb_strpos($_SERVER['PHP_SELF'], 'widget_details.php') !== false ) print ' is-active'; ?>"><a class="nav-inline__link" href="./widget_manager.php">Manage</a></li>
			<li class="nav-inline__item<?php if (mb_strpos($_SERVER['PHP_SELF'], 'widget_styles.php') !== false ) print ' is-active'; ?>"><a class="nav-inline__link" href="./widget_styles.php">Styles</a></li>
			<!--<li><a<?php if (mb_strpos($_SERVER['PHP_SELF'], 'widget_categories.php') !== false ) print ' id="current"'; ?> href="./widget_categories.php">Categories</a></li>-->
		</ul>
	</div>

<?php	
	if (isset($error_array) && isset($error_array['extension'])) {
		print "<h3 class=\"validate_mssg\">Please check that the file is a <span>zip</span> file</h3>";
	}
	else if (isset($error_array) && isset($error_array['upload'])) {
		print "<h3 class=\"validate_mssg\">Could not upload file - it is possible the file is too big</h3>";
	}
	else if (isset($error_array) && isset($error_array['contentJs'])) {
		print "<h3 class=\"validate_mssg\">Script tags are not allowed within the front end JavaScript code</h3>";
	}
	else if (isset($error_array) && sizeof($error_array)>0) {
		print "<h3 class=\"validate_mssg\">Please check that <span>these details*</span> are completed correctly</h3>";
	}
?>
	
	<form id="mainForm" action="widget_details.php<?php print ($widget->id > 0) ? '?internalWidgetID=' . $widget->getInternalID() : ''; ?>" method="post" enctype="multipart/form-data" >
<?php
	if ($widget->id > -1 && ($adminPageAccessPermissions->createContent || $adminPageAccessPermissions->deleteContent && $widgetIsEditable)) {	
?>
	<div id="actionsRow">
	<div class="dropdown">
	<button class="btn action dropdown-toggle">Actions <span class="caret"></span></button>
	<ul class="dropdown-menu">
<?php
	if ($adminPageAccessPermissions->createContent) {
?>
		<input type="hidden" name="duplicateWidget" id="duplicateWidget" value="" />
		<li><a href="#" onclick="$('duplicateWidget').value='1'; $('mainForm').submit(); return false;">Duplicate</a></li>
<?php
	}
	if ($adminPageAccessPermissions->deleteContent && $widgetIsEditable) {
?>
		<li class="divider"></li>
		<input type="hidden" name="delete" id="delete" value="" />
		<li><a href="#" onclick="if (confirmSubmit()) { $('delete').value='1'; $('mainForm').submit(); } return false;">Delete</a></li>
<?php
	}
?>
	</ul>
	</div>
	</div>
<?php
	} 
?>	

		<table class="generic_table">
			<tr>
				<td class="generic_desc<?php if (isset($error_array['title'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Title*</p></td>
				<td class="generic_action">
					<input type="text" name="title" size="33" value="<?php print encodeHtml($widget->title); ?>" <?php if (!$widgetIsEditable) { ?> readonly="readonly"<?php } ?>/>
				</td>
			</tr>
			<tr>
<?php
	if ($widgetIsEditable) {
?>		
						
				<td class="generic_desc"><em><?php print $stepCounter++; ?>.</em> <p><?php print $widget->id > 0 ? 'Update widget' : 'Widget'; ?></p></td>
				<td class="generic_action">
<?php
		if ($widget->id < 1) { 
			print '<div style="display:none;">'; 
		}
?>
					<input type="radio" name="widgetContentType[]" id="widgetContentFile" value="file" checked="checked" onclick="return showWidgetSelection(this.id);" />
					<label for="widgetContentFile">Browse for widget from your local drive</label>
					<br />
<?php
		if ($widget->id < 1) { 
			print '</div>'; 
		}
		else {
?>
					<input type="radio" name="widgetContentType[]" id="widgetContentManual" value="manual" onclick="return showWidgetSelection(this.id);" />
					<label for="widgetContentManual">Manually enter Widget code</label>
					<br />
<?php
		}
?>
					<div id="browseForWidget">
						<input type="file" name="browseFile" />
					</div>
<?php
		if ($widget->id > 0) {
?>
					<div id="manualWidget">
						<p><strong>Front-end <?php print (defined('SCRIPTING_LANGUAGE') && SCRIPTING_LANGUAGE == DOT_NET?' (ascx)':''); ?></strong></p>
						<textarea rows="5" cols="60" id="contentCode" name="contentCode"><?php print encodeHtml($widget->contentCode); ?></textarea>
<?php
			if (SCRIPTING_LANGUAGE == DOT_NET) {
?>
						<p><strong>Front-end Code-behind (ascx.cs)</strong></p>
						<textarea rows="5" cols="60" id="contentCodeBehind" name="contentCodeBehind"><?php print encodeHtml($widget->contentCodeBehind); ?></textarea>
<?php
			}
?>
						<p><strong>Front-end JavaScript</strong></p>
						<textarea rows="5" cols="60" id="contentJs" name="contentJs"><?php print encodeHtml($widget->contentJs); ?></textarea>

						<p><strong>Settings</strong></p>
						<textarea rows="5" cols="60" id="settingsCode" name="settingsCode"><?php print encodeHtml($widget->settingsCode); ?></textarea>

						<p><strong>Settings JavaScript</strong></p>
						<textarea rows="5" cols="60" id="jsCode" name="jsCode"><?php print encodeHtml($widget->jsCode); ?></textarea>
					</div>
<?php
		}
	}
	else {
?>
				<td class="generic_desc"><em><?php print $stepCounter++; ?>.</em> <p>Widget</p></td>
				<td class="generic_action">
					<div id="manualWidgets">
						<p><strong>Front-end <?php print (defined('SCRIPTING_LANGUAGE') && SCRIPTING_LANGUAGE == DOT_NET?' (ascx)':''); ?></strong></p>
						<textarea readonly="readonly" rows="5" cols="60" id="contentCode" name="contentCode"><?php print encodeHtml($widget->contentCode); ?></textarea>
<?php
		if (defined('SCRIPTING_LANGUAGE') && SCRIPTING_LANGUAGE == DOT_NET) {
?>
						<p><strong>Front-end Code-behind (ascx.cs)</strong></p>
						<textarea readonly="readonly" rows="5" cols="60" id="contentCodeBehind" name="contentCodeBehind"><?php print encodeHtml($widget->contentCodeBehind); ?></textarea>
<?php
		}
?>
						<p><strong>Front-end JavaScript</strong></p>
						<textarea readonly="readonly" rows="5" cols="60" id="contentJs" name="contentJs"><?php print encodeHtml($widget->contentJs); ?></textarea>

						<p><strong>Settings</strong></p>
						<textarea readonly="readonly" rows="5" cols="60" id="settingsCode" name="settingsCode"><?php print encodeHtml($widget->settingsCode); ?></textarea>

						<p><strong>Settings JavaScript</strong></p>
						<textarea readonly="readonly" rows="5" cols="60" id="jsCode" name="jsCode"><?php print encodeHtml($widget->jsCode); ?></textarea>
					</div>
<?php 
	}
?>
				</td>
			</tr>
			<tr>
				<td class="generic_desc<?php if (isset($error_array['description'])) { ?>_error<?php } ?>">
					<em><?php print $stepCounter++; ?>.</em> <p>Description*</p>
					<p class="generic_desc_text">Characters left <input readonly type="text" id="remLen" name="remLen" tabindex="-1000" size="3" maxlength="3" value="<?php print (MAX_SUMMARY_CHARS_EVENTS-mb_strlen($widget->description));?>"/></p>
				</td>
				<td class="generic_action">
					<textarea <?php if (!$widgetIsEditable) { ?> readonly="readonly"<?php } ?> rows="3" cols="40" id="description" name="description" onkeydown="textCounter(this, $('remLen'),<?php print MAX_SUMMARY_CHARS_EVENTS;?>);" onkeyup="textCounter(this,$('remLen'),<?php print MAX_SUMMARY_CHARS_EVENTS;?>);"><?php print encodeHtml($widget->description); ?></textarea>
					<?php if (isset($error_array['description'])) { ?><p class="error_help">- Description must not be empty and less than <?php print MAX_SUMMARY_CHARS_EVENTS;?> characters</p><?php } ?>
				</td>
			</tr>
			<tr>
				<td class="generic_desc<?php if (isset($error_array['defaultWidthPercentage'])) { ?>_error<?php } ?>">
					<em><?php print $stepCounter++; ?>.</em> 
					<p>Default Width</p>
					<p class="generic_desc_text">Percentage width</p>
				</td>
				<td class="generic_action">
					<input <?php if (!$widgetIsEditable) { ?> readonly="readonly"<?php } ?> type="text" name="defaultWidthPercentage" size="4" value="<?php print $widget->defaultWidthPercentage!=''?$widget->defaultWidthPercentage:'50'; ?>" />
					<?php if (isset($error_array['defaultWidthPercentage'])) { ?><br /> Default width must be at least 5%<?php } ?>
				</td>
			</tr>
			<tr>
				<td class="generic_desc"><em><?php print $stepCounter++; ?>.</em> <p>Requires Categories</p></td>
				<td class="generic_action">
<?php
	$categoryMatrix = $widget->requiresCategory;
	if (defined('MOD_RETAIL')) {
		$checked = '';
		if ($categoryMatrix - WIDGET_USE_RETAIL_TAXONOMY >= 0) {
			$categoryMatrix -= WIDGET_USE_RETAIL_TAXONOMY;
			$checked = ' checked="checked"';
		}
?>
					<label for="retailTaxonomy"><input <?php if (!$widgetIsEditable) { ?> disabled="disabled"<?php } ?> type="checkbox" class="checkbox" name="requiresCategory[]" id="retailTaxonomy" value="<?php print WIDGET_USE_RETAIL_TAXONOMY; ?>"<?php print $checked; ?> /> Retail Taxonomy</label>
<?php
	}
	
	$checked = '';
	if ($categoryMatrix - WIDGET_USE_MAIN_TAXONOMY >= 0) {
		$categoryMatrix -= WIDGET_USE_MAIN_TAXONOMY;
		$checked = ' checked="checked"';
	}
?>
	<label for="mainTaxonomy"><input <?php if (!$widgetIsEditable) { ?> disabled="disabled"<?php } ?> type="checkbox" class="checkbox" name="requiresCategory[]" id="mainTaxonomy" value="<?php print WIDGET_USE_MAIN_TAXONOMY + WIDGET_USE_IPSV_TAXONOMY; ?>"<?php print $checked; ?> /> <?php print BESPOKE_CATEGORY_LIST_NAME; ?></label>
				</td>
			</tr>
<?php
	if (INSTALLATION_TYPE != GALAXY) {
?>
			<tr>
				<td class="generic_desc"><em><?php print $stepCounter++; ?>.</em> <p>Signed in users only</p></td>
				<td class="generic_action">
					<select name="memberOnly" id="memberOnly" <?php if (!$widgetIsEditable) { ?> readonly="readonly"<?php } ?>>
						<option value="0"<?php if ($widget->memberOnly == '' || $widget->memberOnly == '0') print ' selected="selected"'; ?>>No</option>
						<option value="1"<?php if ($widget->memberOnly == '1') print ' selected="selected"'; ?>>Yes</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="generic_desc"><em><?php print $stepCounter++; ?>.</em> <p>Scope</p></td>
				<td class="generic_action">
					<select name="scope" id="scope" <?php if (!$widgetIsEditable) { ?> readonly="readonly"<?php } ?>>
						<option value="<?php print HomepageWidget::WIDGET_SCOPE_ALL_SITES; ?>"<?php if ($widget->scope == HomepageWidget::WIDGET_SCOPE_ALL_SITES) print ' selected="selected"'; ?>>All sites</option>					
						<option value="<?php print HomepageWidget::WIDGET_SCOPE_THIS_SITE; ?>"<?php if ($widget->scope == HomepageWidget::WIDGET_SCOPE_THIS_SITE) print ' selected="selected"'; ?>>This site only</option>
						<option value="<?php print HomepageWidget::WIDGET_SCOPE_GALAXY_ONLY; ?>"<?php if ($widget->scope == HomepageWidget::WIDGET_SCOPE_GALAXY_ONLY) print ' selected="selected"'; ?>>Galaxies only</option>
						<option value="<?php print HomepageWidget::WIDGET_SCOPE_ENTERPRISE_SITES; ?>"<?php if ($widget->scope == HomepageWidget::WIDGET_SCOPE_ENTERPRISE_SITES) print ' selected="selected"'; ?>>Enterprise sites only</option>
					</select>
				</td>
			</tr>
<?php
	}
	else {
?>
			<input type="hidden" name="memberOnly" value="0" />
			<input type="hidden" name="scope" value="<?php print HomepageWidget::WIDGET_SCOPE_THIS_SITE; ?>" />
<?php
	}
?>
			<tr>
				<td class="generic_desc">
					<em><?php print $stepCounter++; ?>.</em>
					<p>Visible</p>
					<p class="generic_desc_text">Available to use in Homepages designer for <strong>this site only</strong>.</p>
				</td>
				<td class="generic_action">
					<select name="visible" id="visible">
						<option value="1"<?php if ($widget->isVisible()) { ?> selected="selected"<?php } ?>>Yes</option>
						<option value="0"<?php if (!$widget->isVisible()) { ?> selected="selected"<?php } ?>>No</option>
					</select>
				</td>
			</tr>
<?php
			if (($adminPageAccessPermissions->createContent && $widget->id < 1) || ($widget->id > -1 && $adminPageAccessPermissions->updateContent)) {
?>
			<tr>
				<td colspan="2" class="generic_finish"><em><?php print $stepCounter++; ?>.</em>
					<span>
						<input type="submit" class="btn submit" name="uploadWidget" value="<?php print ($widget->id == -1 || $widget->id == ''?'Upload Widget':'Save'); ?>" />
					</span>
				</td>
			</tr>
<?php
			}
?>
		</table>
	</form>
		
		<!-- ################################################ -->
<?php 
    }
    include("../includes/footer.php"); 
?>
