<?php
	require_once('../includes/session_header.php');
	include_once('../includes/global.php');
	include_once("../includes/workflow_restrictions.php");
	include_once('JaduConstants.php');
	include_once('JaduUpload.php');
	include_once('JaduCategories.php');
	include_once('JaduVersions.php');
	include_once('websections/JaduDownloads.php');
	include_once('websections/JaduDownloadPasswords.php');
	include_once('utilities/JaduAdministrators.php');
	include_once('utilities/JaduAdminTasks.php');
	include_once('utilities/JaduReadableURLs.php');

	initWorkflowGlobals(
		$adminService->getCurrentAdmin()->id,
		getAwaitingWebmasterAprovalTask(
			DOWNLOADS_TABLE,
			(isset($_GET['downloadID']) && is_numeric($_GET['downloadID']) && $_GET['downloadID'] > 0 ? (int) $_GET['downloadID'] : -1)
		)
	);

	// Set defaults for undefined constants
	if (!defined('MAX_UPLOAD_FILE_SIZE_BYTES') || MAX_UPLOAD_FILE_SIZE_BYTES == null) {
		if (defined('SCRIPTING_LANGUAGE') && SCRIPTING_LANGUAGE == DOT_NET) {
			define('MAX_UPLOAD_FILE_SIZE_BYTES', ini_get('upload_max_filesize'));
		}
		else {
			define('MAX_UPLOAD_FILE_SIZE_BYTES', sizeToBytes(ini_get('upload_max_filesize')));
		}
	}

	if (!defined('MAX_SUMMARY_CHARS_DOWNLOADS') || MAX_SUMMARY_CHARS_DOWNLOADS == null) {
		define('MAX_SUMMARY_CHARS_DOWNLOADS', 200);
	}

	if (defined('DOWNLOADS_DIRECTORY')) {
		$destinationDir = DOWNLOADS_DIRECTORY;
	}
	else {
		$destinationDir = HOME_DIR . 'var/downloads';
	}

	// Delete download
	if (isset($_POST['deleteDownload']) && $_POST['deleteDownload'] == 1 && isset($_GET['downloadID']) && $_GET['downloadID'] > 0) {
		deleteDownload($_GET['downloadID']);
		deleteDownloadPasswordForDownload($_GET['downloadID']);

		if ($ADMIN_TASKS_ROLES) {
			deleteAdminTaskForObject(DOWNLOADS_TABLE, $_GET['downloadID']);
		}

		header('Location: ./websection_downloads_list.php?statusMessage=' . urlencode('Download deleted'));
		exit();
	}

	// retail integration
	if (!isset($excludedFieldList)) {
		$excludedFieldList = array();
	}
	if (!isset($retail)) {
		$retail = false;
	}
	else {
		$modulePagePermissions = getModulePageFromURL('/websections/websection_downloads.php');
	   	if ($modulePagePermissions->parent_id > -1) {
	   		$modulePageParent = getModulePage($modulePagePermissions->parent_id);
	   		$adminPageAccessPermissions = getAdminPageAccess($adminService->getCurrentAdminID(), $modulePageParent->id);
	   	}
	   	else {
	   		$adminPageAccessPermissions = getAdminPageAccess($adminService->getCurrentAdminID(), $modulePagePermissions->id);
	   	}
	}
	// end retail integration

	// Initialise the category strings
	$bespokeString = '';
	$taxonomyString = '';

	$errors = array();
	$invalidFileType = false;
	$fileSizeError = false;
	$filePermissionsError = false;
	$fileTransferError = false;
	$brokenFileError = false;
	$noFilesSelectedError = false;

	$viewFile = false;
	$viewLink = false;

	// Delete files
	if (isset($_POST['deleteFile']) && $_POST['fileID'] && $_POST['fileID'] > 0) {
		deleteDownloadFile($_GET['downloadID'], $_POST['fileID']);

		$statusMessage = 'Download file deleted';
		unset($_POST['fileID'], $_REQUEST['fileID']);
	}
	else if (isset($_POST['deleteFiles']) && count($_POST['deleteID']) > 0) {
		deleteDownloadFiles($_GET['downloadID'], $_POST['deleteID']);

		if (count($_POST['deleteID']) > 1) {
			$statusMessage = 'The selected items have been deleted';
		}
		else {
			$statusMessage = 'The selected item has been deleted';
		}
	}

	// Retrieve the selected download (and download file)
	if (isset($_GET['downloadID']) && $_GET['downloadID'] > 0) {
		$download = getDownload($_GET['downloadID']);

		// Retrieve the selected download file
		if (isset($_REQUEST['fileID']) && $_REQUEST['fileID'] > 0) {
			$file = getDownloadFile($_REQUEST['fileID']);
			if (!empty($file->url)) {
				$viewLink = true;
			}
			else {
				$viewFile = true;
			}
		}
		else {
			$file = new DownloadFile();
		}

		// Retrieve the categories for the download
		$categories = getAllCategories(DOWNLOADS_CATEGORIES_TABLE, $download->id);
		foreach($categories as $category) {
			if ($category->categoryType == BESPOKE_CATEGORY_LIST_NAME) {
				if (mb_strlen($bespokeString) > 0) {
					$bespokeString .= ",";
				}
				$bespokeString .= $category->categoryID;
			}
			else if ($category->categoryType == TAXONOMY_NAME) {
				if (mb_strlen($taxonomyString) > 0){
					$taxonomyString .= ",";
				}
				$taxonomyString .= $category->categoryID;
			}
		}
	}
	else if (!$retail) {
		$download = new Download();
		$file = new DownloadFile();
	}

	$usePassword = (isset($_POST['passwordEnabled']) && $_POST['passwordEnabled'] == 1);

	// Save download
	if (isset($_POST['saveDownload'])) {
		$download->title = isset($_POST['title']) ? trim($_POST['title']) : '';
		$download->description = isset($_POST['description']) ? trim($_POST['description']) : '';

		$errors = validateDownload($download, $_POST['categories:bespoke'], $_POST['categories:taxonomy']);

		if ($usePassword && isset($_POST['changePassword']) && $_POST['changePassword']) {
			if (!isset($_POST['downloadPassword']) || strlen($_POST['downloadPassword']) < 1) {
				$errors['password'] = true;
			}
		}

		if (empty($errors)) {
			// Save the download password if there is one
			if ($usePassword) {
				if (isset($_POST['changePassword']) && $_POST['changePassword'] == 1 && isset($_POST['downloadPassword'])) {
					$downloadPassword = getDownloadPasswordForDownload($download->id);
					$downloadPassword->downloadID = $download->id;
					$passwordService = $jadu->getJaduSecurityPassword();
					$passwordService->generate(trim($jadu->getInput()->post('downloadPassword')));
					$downloadPassword->password = $passwordService->getPassword();
					$downloadPassword->id = newDownloadPassword($downloadPassword);
					$download->passwordID = $downloadPassword->id;
				}
			}
			else {
				$download->passwordID = -1;
			}

			if ($download->id < 1) {
				// Insert download
				$download->creatorID = $adminService->getCurrentAdminID();
				$download->id = newDownload($download);

				if ($download->passwordID > 0) {
					// Update the download password with the inserted ID
					$downloadPassword->downloadID = $download->id;
					updateDownloadPassword($downloadPassword);
				}

				// Create the download sub-directory for this download
				if (!file_exists($destinationDir . '/' . $download->id)) {
					mkdir($destinationDir . '/' . $download->id, 0777);
				}

				// Add to categories
				if (isset($_POST['categories:bespoke'])) {
					$bespokeString = $_POST['categories:bespoke'];
					$bespokeCategories = explode(',', $bespokeString);
					if (count($bespokeCategories) > 0) {
						foreach($bespokeCategories as $id) {
							enterCategory(DOWNLOADS_CATEGORIES_TABLE, BESPOKE_CATEGORY_LIST_NAME, $id, $download->id, $download->visible);
						}
					}
				}
				if (isset($_POST['categories:taxonomy'])) {
					$taxonomyString = $_POST['categories:taxonomy'];
					$taxonomyCategories = explode(',', $taxonomyString);
					if (count($taxonomyCategories) > 0) {
						foreach($taxonomyCategories as $id) {
							enterCategory(DOWNLOADS_CATEGORIES_TABLE, TAXONOMY_NAME, $id, $download->id, $download->visible);
						}
					}
				}

				header('Location: ./websection_downloads.php?downloadID=' . $download->id . '&statusMessage=Download Saved');
				exit;
			}
			else {
				// Update download
				updateDownload($download);
				deleteCategories(DOWNLOADS_CATEGORIES_TABLE, $download->id, $download->visible);

				// Add to categories
				if (isset($_POST['categories:bespoke'])) {
					$bespokeString = $_POST['categories:bespoke'];
					$bespokeCategories = explode(',', $bespokeString);
					if (count($bespokeCategories) > 0) {
						foreach($bespokeCategories as $id) {
							enterCategory(DOWNLOADS_CATEGORIES_TABLE, BESPOKE_CATEGORY_LIST_NAME, $id, $download->id, $download->visible);
						}
					}
				}
				if (isset($_POST['categories:taxonomy'])) {
					$taxonomyString = $_POST['categories:taxonomy'];
					$taxonomyCategories = explode(',', $taxonomyString);
					if (count($taxonomyCategories) > 0) {
						foreach($taxonomyCategories as $id) {
							enterCategory(DOWNLOADS_CATEGORIES_TABLE, TAXONOMY_NAME, $id, $download->id, $download->visible);
						}
					}

				}

				$statusMessage = 'Download saved';
			}
		}
	}

	// Change item position
	if (isset($_GET['move']) && isset($_GET['moveFileID'])) {
		if (moveDownloadFile($_GET['moveFileID'], $_GET['move'])) {
			$statusMessage = 'The download file has been moved';
		}
		else {
			$statusMessage = 'The download file could not be moved';
		}
	}

	// Save files
	if ($download->id > 0 && ($file->id == -1 || ($file->id > 0 && $file->downloadID = $download->id)) && (isset($_POST['saveFile']) || isset($_POST['saveLink']))) {
		// Initialise the errors array
		$errors = array();

		if (isset($_POST['saveFile'])) {
			// Validate the title
			if (isset($_POST['fileTitle'])) {
				$file->title = $_POST['fileTitle'];
				if (empty($_POST['fileTitle'])) {
					$errors['fileTitle'] = true;
				}
			}
			else {
				$errors['fileTitle'] = true;
			}

			// Reset un-used variables
			$file->url = '';

			// Just changing the title
			if (!isset($_POST['changeFile']) && $file->id != -1) {
				updateDownloadFile($file);

				$statusMessage = 'Download file saved';
				$viewFile = false;
			}
			// Selected a file from WebDAV bulk upload list
			else if (isset($_POST['upload_method']) && $_POST['upload_method'] == 'webdav'
			&& isset($_POST['webdavFile']) && !empty($_POST['webdavFile'])
			&& isset($_POST['webdavFileFilter']) && !empty($_POST['webdavFileFilter'])) {
				$webdavPath = HOME_DIR . 'var/webdav/dropbox/';
				if (isset($_POST['webdavFileFilter']) && $_POST['webdavFileFilter'] == 'admin') {
					$webdavPath .= 'private/' . $adminService->getCurrentAdmin()->username . '/';
				}
				else {
					$webdavPath .= 'public/';
				}
				$webdavPath .= 'downloads/';

				$webdavFilePath = $webdavPath . $_POST['webdavFile'];

				if (!file_exists($webdavFilePath)) {
					$errors['webdavFile'] = true;
				}

				$file->filename = cleanFilename($_POST['webdavFile']);
				$file->mimeType = mimeContentType($webdavFilePath);
				$extension = mb_substr($file->filename, mb_strrpos($file->filename, '.') + 1);

				// Check if the file type is supported by the CMS
				if (!isFileTypeSupported($extension)) {
					$errors['webdavFile'] = true;
					$invalidFileType = true;
				}

				if (empty($errors)) {
					$file->size = filesize($webdavFilePath);
					$file->filemd5 = md5_file($webdavFilePath);

					if ($file->id == -1) {
						$file->downloadID = $download->id;
						$file->id = newDownloadFile($file);
					}
					else {
						updateDownloadFile($file);
					}

					$downloadFilePath = $destinationDir . '/' . $download->id . '/' . $file->id;

					// Create the download sub-directory for this download
					if (!file_exists($downloadFilePath)) {
						@mkdir($downloadFilePath, 0777);
					}

					// Only copy the file if it doesn't already exist
					if (!file_exists($downloadFilePath . '/' . $file->filemd5)) {
						rename($webdavFilePath, $downloadFilePath . '/' . $file->filemd5);
						if (class_exists('Jadu_ClusterSync')) {
							Jadu_ClusterSync::write($downloadFilePath . '/' . $file->filemd5);
						}
					}

					if ($retail) {
						assignDownloadFileToProduct($product->id, $file);
					}

					$statusMessage = 'Download file saved';
					$viewFile = false;
				}
			}
			// Uploading a file
			else if (isset($_POST['upload_method']) && $_POST['upload_method'] == 'upload'
			&& isset($_FILES['downloadFileBrowse'])) {
				switch ($_FILES['downloadFileBrowse']['error']) {
					case 1: // UPLOAD_ERR_INI_SIZE:
					case 2: // UPLOAD_ERR_FORM_SIZE:
						$viewFile = true;
						$errors['downloadFileBrowse'] = true;
						$fileSizeError = true;
						if (file_exists($_FILES['downloadFileBrowse']['tmp_name'])) {
							@unlink($_FILES['downloadFileBrowse']['tmp_name']);
						}
						break;

					case 3: // UPLOAD_ERR_PARTIAL:
					case 4: // UPLOAD_ERR_NO_FILE:
						$viewFile = true;
						$errors['downloadFileBrowse'] = true;
						$brokenFileError = true;
						break;

					case 6: // UPLOAD_ERR_NO_TMP_DIR:
					case 7: // UPLOAD_ERR_CANT_WRITE:
					case 8: // UPLOAD_ERR_EXTENSION:
						$viewFile = true;
						$errors['downloadFileBrowse'] = true;
						$fileTransferError = true;
						break;

					case 0: // UPLOAD_ERR_OK:
					default:
						$file->size = filesize($_FILES['downloadFileBrowse']['tmp_name']);

						if (file_exists($_FILES['downloadFileBrowse']['tmp_name']) && $file->size > 0) {
							$file->filename = cleanFilename($_FILES['downloadFileBrowse']['name']);
							$file->mimeType = $_FILES['downloadFileBrowse']['type'];
							$extension = mb_substr($file->filename, mb_strrpos($file->filename, '.') + 1);

							// Check if the file type is supported by the CMS
							if (!isFileTypeSupported($extension)) {
								$errors['downloadFileBrowse'] = true;
								$invalidFileType = true;
								@unlink($_FILES['downloadFileBrowse']['tmp_name']);
								break;
							}
							// Double check the filesize
							else if ($file->size > MAX_UPLOAD_FILE_SIZE_BYTES) {
								$errors['downloadFileBrowse'] = true;
								$fileSizeError = true;
								@unlink($_FILES['downloadFileBrowse']['tmp_name']);
								break;
							}
							// Ensure the file had been uploaded and not spoofed
							else if (!is_uploaded_file($_FILES['downloadFileBrowse']['tmp_name'])) {
								$errors['downloadFileBrowse'] = true;
								$fileTransferError = true;
								@unlink($_FILES['downloadFileBrowse']['tmp_name']);
								break;
							}

							// Generate the MD5 hash of the file contents
							$file->filemd5 = md5_file($_FILES['downloadFileBrowse']['tmp_name']);

							if ($file->filemd5 === false) {
								$errors['downloadFileBrowse'] = true;
								$brokenFileError = true;
								@unlink($_FILES['downloadFileBrowse']['tmp_name']);
							}
						}
						else {
							$errors['downloadFileBrowse'] = true;
							$brokenFileError = true;
							@unlink($_FILES['downloadFileBrowse']['tmp_name']);
						}

						if (empty($errors)) {
							$uploadResult = false;
							$newDownloadFile = false;

							if ($file->id == -1) {
								$file->downloadID = $download->id;
								$file->id = newDownloadFile($file);
								$newDownloadFile = true;
							}

							$downloadFilePath = $destinationDir . '/' . $download->id;
							if (!file_exists($downloadFilePath)) {
								mkdir($downloadFilePath, 0777);
							}

							$downloadFilePath .= '/' . $file->id;
							if (!file_exists($downloadFilePath)) {
								mkdir($downloadFilePath, 0777);
							}

							$uploadResult = false;
							if (file_exists($downloadFilePath) && !empty($file->id)) {
								// Only upload the file if it doesn't already exist
								if (!file_exists($downloadFilePath . '/' . $file->filemd5)) {
									$uploadResult = uploadFile($_FILES['downloadFileBrowse']['tmp_name'], $downloadFilePath . '/' . $file->filemd5);
								}
								else {
									// File already exists
									$uploadResult = true;
								}
							}

							if ($uploadResult) {
								// The file was saved
								$statusMessage = 'Download file saved';
								$viewFile = false;

								if (!$newDownloadFile) {
									updateDownloadFile($file);
								}

								if ($retail) {
									assignDownloadFileToProduct($product->id, $file);
								}
							}
							else {
								// The file could not be saved
								$errors['downloadFileBrowse'] = true;
								$brokenFileError = true;
								if ($newDownloadFile) {
									// New DownloadFile record inserted but file could not be saved, delete the record
									deleteDownloadFile($file->downloadID, $file->id);
								}
							}
						}
				}
			}
			else {
				// No files selected, display an error
				$errors['downloadFileBrowse'] = true;
			}

			unset($_POST['upload_method']);
		}
		else if (isset($_POST['saveLink'])) {
			$viewLink = true;

			// Validate the title
			if (isset($_POST['linkTitle'])) {
				$file->title = $_POST['linkTitle'];
				if (empty($_POST['linkTitle'])) {
					$errors['linkTitle'] = true;
				}
			}
			else {
				$errors['linkTitle'] = true;
			}

			// Validate the URL
			if (isset($_POST['url'])) {
				$file->url = $_POST['url'];
				if (!validateURL($_POST['url'])) {
					$errors['url'] = true;
				}
			}
			else {
				$errors['url'] = true;
			}

			// Validate the file size
			if (isset($_POST['size']) && isset($_POST['selectSize'])) {
				$file->size = convertSizeInputToBytes($_POST['size'], $_POST['selectSize']);
			}
			else {
				$errors['size'] = true;
			}

			// Reset un-used variables
			$file->filename = '';
			$file->mimeType = '';

			if (empty($errors)) {
				if ($file->id == -1) {
					$file->downloadID = $download->id;
					$file->id = newDownloadFile($file);
				}
				else {
					updateDownloadFile($file);
				}

				$statusMessage = 'Download link saved';
				$viewLink = false;
			}
		}
	}


	if ($download->id != -1 && empty($errors)) {
		// Live
		if (isset($_POST['live'])) {
			$download->live = (bool) $_POST['live'];
			setDownloadLiveStatus($download->id, $download->live);

			if (!$download->live) {
				$download->visible = 0;
				$categories = getAllCategories(DOWNLOADS_CATEGORIES_TABLE, $download->id);
				foreach ($categories as $category) {
					updateAppliedCategories($category->categoryType, $category->categoryID, DOWNLOADS_CATEGORIES_TABLE, $download->visible);
				}
			}
		}

		// Visible
		if (isset($_POST['visible']) && $download->live) {
			$download->visible = (bool) $_POST['visible'];
			setDownloadVisibleStatus($download->id, $download->visible);

			$categories = getAllCategories(DOWNLOADS_CATEGORIES_TABLE, $download->id);
			foreach ($categories as $category) {
				updateAppliedCategories($category->categoryType, $category->categoryID, DOWNLOADS_CATEGORIES_TABLE, $download->visible);
			}
		}

		// Workflow
		if (isset($_POST['approve'])) {
			$task = getAdminTask($_POST['taskID']);
			if ($THIS_WORKFLOW->id == -1 || $ADMIN_MUST_APPROVE) {
				$versions = new Versions($task->dbTable, $task->objectID, VERSIONED_DOWNLOADS_TABLE);
				$download->modDate = time();
				$downloadVersionID = $versions->addVersion($download, VERSIONED_DOWNLOADS_TABLE);
				if ($downloadVersionID != -1) {
					createDownloadVersionMappings($download->id, $downloadVersionID);
				}
				$versions->setLatestVersionCurrent(VERSIONED_DOWNLOADS_TABLE);
				deleteDownloadsCache();
				Jadu_Service_Container::getInstance()->getEventContainer()->fire('download.approve', (new Jadu_Page_Download_Event_Object($download, $adminService->getCurrentAdmin())));
				deleteAdminTaskForObject($task->dbTable, $task->objectID);
				$statusMessage = 'Download approved';
			}
			else {
				deleteAdminTaskForObject($task->dbTable, $task->objectID);
				newAdminTask ("", ADMIN_TASK_ACTION_DEPLOY, $task->dbTable, $task->objectID, $task->objectTitle, $adminService->getCurrentAdmin()->id, $task->pageURL, $task->pageTitle, $task->workflowID, $nextWorkflowAdminLevel->id, "");
				$statusMessage = 'Download sent for approval';
			}
		}

		if ($ADMIN_TASKS_ROLES && isset($_POST['submitToWebmaster']) && $_POST['submitToWebmaster'] > -1) {
			$pageURL = '/websections/websection_downloads.php?downloadID=' . $download->id;
			if ($_POST['submitToWebmaster'] == 0) {
				deleteAdminTaskForObject(DOWNLOADS_TABLE, $download->id);
				newAdminTask('', ADMIN_TASK_ACTION_DEPLOY, DOWNLOADS_TABLE, $download->id, $download->title, $adminService->getCurrentAdminID(), $pageURL, 'Downloads', $THIS_WORKFLOW->id, $nextWorkflowAdminLevel->id, '');
			}
			else {
				deleteAdminTaskForObject(DOWNLOADS_TABLE, $download->id);
				newAdminTask($_POST['submitToWebmaster'], ADMIN_TASK_ACTION_DEPLOY, DOWNLOADS_TABLE, $download->id, $download->title, $adminService->getCurrentAdminID(), $pageURL, 'Downloads', $THIS_WORKFLOW->id, $nextWorkflowAdminLevel->id, '');
			}
			$statusMessage = 'Download sent for approval';
		}
	}

	// Retrieve all the download files for the selected download
	if ($download->id == -1) {
		$allFiles = array();
	}
	else {
		$allFiles = getAllDownloadFilesForDownload($download->id);
	}
	$numFiles = count($allFiles);

	// To create the numbered steps
	$stepCounter = 1;

	addJavascript('javascript/lightbox.js');
	addJavascript('javascript/workflow.js');

	include("../includes/head.php");
	if ($retail) {
		include('../retail/retail_product_details_tab_include.php');
	}

	if ($download->id > 0 && !canAdminViewContentItem($adminService->getCurrentAdmin(), getWorkflowAdminAssignedCategoryIds($adminService->getCurrentAdmin()->id), getAllCategoryIDs(DOWNLOADS_CATEGORIES_TABLE, $download->id), $download->creatorID, adminHasTaskForItem($adminService->getCurrentAdmin()->id, $adminService->getCurrentAdmin()->adminLevelID, $workflowIDs, DOWNLOADS_TABLE, $download->id))) {
?>
	<div class="not_yet"><h3>You do not have permission to view this download.</h3></div>
<?php
	}
	else if ($download->id < 1 && !$adminPageAccessPermissions->createContent) {
?>
	<div class="not_yet"><h3>You do not have permission to create a new download.</h3></div>
<?php
	}
	else {
?>
<script type="text/javascript">
	function showFile(style, value)
	{
		document.getElementById('fileArea').style.display = style;
		document.mainForm.viewFile.value = value;
	}
	function showLink(style, value)
	{
		document.getElementById('linkArea').style.display = style;
		document.mainForm.viewLink.value = value;
	}
	function viewFileFunction()
	{
		var bool = document.mainForm.viewFile.value;
		if (bool == "true") {
			showFile("none", "false");
		}
		else {
			showFile("block", "true");
			showLink("none", "false");
		}

		location.href = location.href + '#fileArea';
	}
	function viewLinkFunction()
	{
		var bool = document.mainForm.viewLink.value;
		if (bool == "true") {
			showLink("none", "false");
		}
		else {
			showLink("block", "true");
			showFile("none", "false");
		}

		location.href = location.href + '#linkArea';
	}
	function clearFile()
	{
		showFile("block", "true");
		showLink("none", "false");
		document.fileForm.fileID.value = "-1";
		document.fileForm.fileTitle.value = "";
		document.fileForm.changeFile.checked = true;
		document.getElementById('change_file').style.display = 'none';
		showFileUploadRow('');
	}
	function clearLink()
	{
		showLink("block", "true");
		showFile("none", "false");
		document.linkForm.fileID.value = "-1";
		document.linkForm.linkTitle.value = "";
		document.linkForm.url.value = "";
		document.linkForm.size.value = "0";
	}
	function getField(form, fieldName)
	{
		if (!document.all)
			return form[fieldName];
		else  // IE has a bug not adding dynamically created field
			// as named properties so we loop through the elements array
		for (var e = 0; e < form.elements.length; e++)
		  if (form.elements[e].name == fieldName)
			return form.elements[e];
		return null;
	}
	function removeField(form, fieldName)
	{
		var field = getField (form, fieldName);
		if (field && !field.length)
			field.parentNode.removeChild(field);
	}
	function showFileUploadRow(value)
	{
		if (document.fileForm.changeFile.checked) {
			if (is_ie && value == '' && ie_version < 10) {
				document.getElementById('upload_row').style.display = 'block';
			}
			else {
				document.getElementById('upload_row').style.display = value;
			}
		}
		else {
			document.getElementById('upload_row').style.display = 'none';
		}
	}
	function showPasswordFields()
	{
		if (document.getElementById('changePassword').checked == true) {
			document.getElementById('passwordFields').style.display = '';
		}
		else {
			document.getElementById('passwordFields').style.display = 'none';
		}
	}
	</script>
<?php
	/*if (!$retail) {
?>
		<p class="edit_doc">
			<a href="./websection_downloads_list.php">Back to downloads list</a>
		</p>
<?php
	}
	else {
?>
		<p class="edit_doc">
			<a href="retail_product_details_downloads.php?productID=<?php print intval($product->id); ?>">Back to product downloads list</a>
		</p>
<?php
	}*/

	// Error messages
	if ($invalidFileType) {
?>
	<h4 class="validate_mssg">The file extension you have uploaded is not supported.</h4>
<?php
	}

	else if ($fileSizeError) {
?>
	<h4 class="validate_mssg">The file you have uploaded is larger than the allowed size, Please make sure your file is <span>less than <?php print formatFilesize(MAX_UPLOAD_FILE_SIZE_BYTES) ?></span></h4>
<?php
	}

	else if ($filePermissionsError) {
?>
	<h4 class="validate_mssg">Could not save file, permission denied. Unable to write to <span><?php print $destinationDir; ?></span> Please contact Jadu Support.</h4>
<?php
	}

	else if ($fileTransferError) {
?>
	<h4 class="validate_mssg">There was an error during the file upload. Please try again.</h4>
<?php
	}

	else if ($brokenFileError) {
?>
	<h4 class="validate_mssg">There was an error during the file upload. Either no file was selected or the file was lost during transfer. Please try again.</h4>
<?php
	}

	else if ($noFilesSelectedError) {
?>
	<h4 class="validate_mssg">You have not selected any download files or links to be deleted.</h4>
<?php
	}

	if (count($errors) > 0) {
?>
	<h4 class="validate_mssg">Please check that <span>these details*</span> are completed correctly</h4>
<?php
	}

	if ($retail) {
?>
		<form name="mainForm" id="mainForm" action="./retail_product_details_download_detail.php?productID=<?php print intval($product->id); ?>&downloadID=<?php print $download->id; ?>" method="post" enctype="multipart/form-data">
<?php
	}
	else {
?>
		<form name="mainForm" id="mainForm" action="./websection_downloads.php?downloadID=<?php print $download->id; ?>" method="post" enctype="multipart/form-data">
<?php
	}
?>
	<div id="actionsRow">
	<div class="dropdown">
	<button class="btn action dropdown-toggle">Actions <span class="caret"></span></button>
	<ul class="dropdown-menu">
<?php
	if ($download->id > 0) {
		$arguments = "parentContentItemID=" . $download->id . "&parentTable=" . DOWNLOADS_TABLE . "&parentContentTitle=" . str_replace("'", "\'", urlencode($download->title));
		$contentHistoryLightBox = "loadLightbox('content_history', 'lb', '$arguments');";
?>
		 <li>
			<a class="history" onclick="<?php print $contentHistoryLightBox; ?>; return false;" href="#">View History</a>
		 </li>
<?php
		if ($adminPageAccessPermissions->updateContent) {
			if ($retail) {
?>
			<li><a href="<?php print SECURE_JADU_PATH . '/utils/track_changes.php?productID=' . $product->id . '&downloadID=' . $download->id; ?>">Track Changes</a></li>
<?php
			}
			else {
?>
			<li><a href="<?php print SECURE_JADU_PATH . '/utils/track_changes.php?downloadID=' . $download->id; ?>">Track Changes</a></li>
<?php
			}
		}
		if (!$retail && $download->live == 1) {
?>
			<li class="divider"></li>
			<li><a class="external" href="http://<?php print DOMAIN . buildDownloadsURL(-1, -1, $download->id); ?>" target="_blank">View live</a></li>
<?php
		}
		if ($adminPageAccessPermissions->deleteContent) {
?>
			<li class="divider"></li>
			<input type="hidden" name="deleteDownload" id="deleteDownload" value="" />
			<li><a href="#" onclick="if (confirmSubmit()) { $('deleteDownload').value='1'; $('mainForm').submit(); } return false;">Delete</a></li>
<?php
		}
		if (!$site->isMainSite) {
			$contentTypeDataMapper = new Jadu_ContentType_DataMapper(Jadu_Service_Container::getInstance()->getDB(), new Jadu_CacheManager());
			$contentType = $contentTypeDataMapper->getByTableName(DOWNLOADS_TABLE);

			$translationTaskHelper = new Jadu_Translations_Task();
			$argumentString = Jadu_Translations_Mapping::buildViewLightboxArgString($download->id, $download->title, DOWNLOADS_TABLE, MICROSITE_ID, $contentType->id);
			$viewTranslationsLightbox = "loadLightbox('translations/view_translations', 'lb', '$argumentString');";
			$availableLanguages = $translationTaskHelper->getAvailableLanguagesForTranslationTask($contentType->getID(), $download->id, MICROSITE_ID);
			if (count($availableLanguages) > 0) {
				$arguments = "itemID=" . $download->id . "&tableName=". DOWNLOADS_TABLE . "&micrositeID=" . MICROSITE_ID;
				$createTranslationTaskLightBox = "loadLightbox('translations/translations', 'lb', '$arguments')";
?>
				<li class="divider"></li>
				<li>
					<a class="createTask" onclick="<?php print $createTranslationTaskLightBox; ?>; return false;" href="#">Translate</a>
				</li>
<?php
			}
			if(!checkGalaxyNotInTranslationGroup(MICROSITE_ID)) {
?>
				<li class="divider"></li>
				<li>
					<a onclick="<?php print $viewTranslationsLightbox; ?> return false;" href="#">View Translations</a>
				</li>
<?php
			}
		}
	}
?>
	</ul>
	</div>
<?php
	if ($download->id > 0) {
		if (awaitingWebmasterAproval(DOWNLOADS_TABLE, $download->id)) {
			$task = getAwaitingWebmasterAprovalTask(DOWNLOADS_TABLE, $download->id);
			if (canAdminApproveTask($adminService->getCurrentAdmin(), $task, $THIS_WORKFLOW)) {
?>
			<input type="hidden" name="taskID" value="<?php print $task->id;?>">
			<input type="submit" class="btn success ckBeforeSave" name="approve" value=" Approve " onclick="return approveTask(<?php print $task->id;?>);">
			<input type="button" class="btn danger" name="reject" value=" Decline " onclick="loadTaskSubmit('<?php print $task->id;?>','<?php print ADMIN_TASK_ACTION_REJECT; ?>','<?php print encodeHtml($task->pageTitle) ?>', '<?php print $THIS_WORKFLOW->id; ?>','','<?php print encodeHtml($_SERVER['QUERY_STRING']); ?>')" />
<?php
			}
			else {
?>
				<h3>This Item has been submitted
<?php
					if ($task->adminID != 0) {
						$submittedTo = $adminService->getAdministrator($task->adminID);
						print 'to '.$submittedTo->name;
					}
?>
				 for approval</h3>
<?php
			}
		}
		else {
			if ($THIS_WORKFLOW->id != -1 && !empty($COLLABORATION_ADMINS)) {
?>
			<input type="hidden" name="submitCollaboration" id="submitCollaboration" value="-1" />
			<div class="dropdown">
				<button class="btn sort dropdown-toggle">Collaborate <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<li>
						<a href="#" onclick="$('submitCollaboration').value='0'; loadTaskCollaborate('<?php print $download->id;?>','<?php print ADMIN_TASK_ACTION_COLLABORATE; ?>', 'Downloads', '<?php print $THIS_WORKFLOW->id; ?>','','<?php print $_SERVER['QUERY_STRING']; ?>'); return false;">Yes</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="#" onclick="$('submitCollaboration').value='-1'; loadTaskCollaborate('<?php print $download->id;?>','<?php print ADMIN_TASK_ACTION_COLLABORATE; ?>', 'Downloads', '<?php print $THIS_WORKFLOW->id; ?>','','<?php print $_SERVER['QUERY_STRING']; ?>'); return false;">No</a>
					</li>


<?php
				foreach ($COLLABORATION_ADMINS as $adminForCollaborate) {
?>
					<li>
						<a href="#" onclick="$('submitCollaboration').value='<?php print $adminForCollaborate->id ?>'; loadTaskCollaborate('<?php print $download->id;?>','<?php print ADMIN_TASK_ACTION_COLLABORATE; ?>', 'Downloads', '<?php print $THIS_WORKFLOW->id; ?>','','<?php print $_SERVER['QUERY_STRING']; ?>'); return false;"><?php print $adminForCollaborate->name; ?> </a>
					</li>
<?php
				}
?>
				</ul>
			</div>
<?php
			}
?>
				<input type="hidden" name="submitToWebmaster" id="submitToWebmaster" value="-1" />
<?php
			if (is_array($PROOFING_ADMINS) && !empty($PROOFING_ADMINS)) {
?>
				<div class="dropdown">
				<button class="btn submit dropdown-toggle">Submit <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<li><a href="#" onclick="$('submitToWebmaster').value='0'; $('mainForm').submit(); return false;">Yes</a></li>
<?php
				foreach ($PROOFING_ADMINS as $adminForProofing) {
?>
					<li><a href="#" onclick="$('submitToWebmaster').value='<?php print $adminForProofing->id;?>'; $('mainForm').submit(); return false;"><?php print encodeHtml($adminForProofing->name); ?></a></li>
<?php
				}
?>
				</ul>
				</div>
<?php
			}
			else {
?>
					<a href="#" class="linkSubmit" onclick="$('submitToWebmaster').value='0'; $('mainForm').submit(); return false;">Submit</a>
<?php
			}

			if ($download->id != -1) {
				$versions = getDownloadsPreviousVersions($download->id);
				if ($versions->liveVersion != -1) {
?>
		</div>
		<div class="contentStatus">
		<input type="hidden" name="live" id="live" value="<?php print $download->live; ?>" />
<?php
					if ($download->live == 1) {
?>
			<span class="switch"><span>Live</span><a href="#" onclick="$('live').value='0'; $('mainForm').submit(); return false;">Take offline</a></span>
			<input type="hidden" name="visible" id="visible" value="<?php print $download->visible; ?>" />
<?php
						if ($download->visible == 1) {
?>
			<span class="switch"><span>Visible</span><a href="#" onclick="$('visible').value='0'; $('mainForm').submit(); return false;">Make invisible</a></span>
<?php
						}
						else {
?>
			<span class="switch switchOff"><a href="#" onclick="$('visible').value='1'; $('mainForm').submit(); return false;">Make Visible</a><span>Invisible</span></span>
<?php
						}
					}
					else {
?>
			<span class="switch switchOff"><a href="#" onclick="$('live').value='1'; $('mainForm').submit(); return false;">Make live</a><span>Offline</span></span>
<?php
					}
				}
			}
		}
?>
		</div>
<?php
	}
?>
		<!-- Download form -->
		<input type="hidden" name="viewFile" value="<?php print $viewFile; ?>" />
		<input type="hidden" name="viewLink" value="<?php print $viewLink; ?>" />
		<input type="hidden" id="categories:bespoke" name="categories:bespoke" value="<?php print $bespokeString ?>" />
		<input type="hidden" id="categories:taxonomy" name="categories:taxonomy" value="<?php print $taxonomyString ?>" />
		<table class="generic_table">
			<tr>
				<td class="generic_desc<?php if (isset($errors['title'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Title of download*</p></td>
				<td class="generic_action"><input type="text" name="title" id="title" size="42" value="<?php print encodeHtml($download->title);?>"></td>
			</tr>
			<tr>
				<td class="generic_desc<?php if (isset($errors['bespokeCategories']) || isset($errors['taxonomyCategories'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Categories*</p></td>
				<td class="generic_action"><input type="button" class="btn interimButton" id="assignCategories" value="Assign Categories" onclick="return loadLightbox('assign_category', 'lb', '');"></td>
			</tr>
			<tr>
				<td class="generic_desc">
					<em><?php print $stepCounter++; ?>.</em>
					<p>Description of download</p>
					<p class="generic_desc_text">Characters left <input readonly="readonly" type="text" id="remLen" name="remLen" size="3" maxlength="3" value="<?php print (MAX_SUMMARY_CHARS_DOWNLOADS - mb_strlen($download->description));?>" /></p>
				</td>
				<td class="generic_action">
					<textarea name="description" id="content" rows="3" cols="40" onkeydown="textCounter(document.mainForm.description,document.mainForm.remLen,<?php print MAX_SUMMARY_CHARS_DOWNLOADS;?>);" onkeyup="textCounter(document.mainForm.description,document.mainForm.remLen,<?php print MAX_SUMMARY_CHARS_DOWNLOADS;?>);"><?php print encodeHtml($download->description);?></textarea>
				</td>
			</tr>
<?php
	if (isset($excludedFieldList) && !in_array('passwordEnabled', $excludedFieldList)) {
?>
			<tr>
				<td class="generic_desc<?php if (isset($errors['password'])) { ?>_error<?php } ?>">
					<em><?php print $stepCounter++; ?>.</em>
					<p>Enable download password?</p>
				</td>
				<td class="generic_action">
					<select class="select" name="passwordEnabled" id="passwordEnabled"	onchange="if(this.value=='1') {document.getElementById('setPasswordBlock').style.display = 'block';} else {document.getElementById('setPasswordBlock').style.display = 'none';}">
						<option value="1"<?php if (($download->passwordID > 0 && !isset($_POST['passwordEnabled'])) || (isset($_POST['passwordEnabled']) && $_POST['passwordEnabled'])) { print ' selected="selected"'; } ?> >Yes</option>
						<option value="0"<?php if (($download->passwordID < 1 && !isset($_POST['passwordEnabled'])) || (isset($_POST['passwordEnabled']) && !$_POST['passwordEnabled'])) { print ' selected="selected"'; } ?> >No</option>
					</select>

					<p id="setPasswordBlock" style="display:<?php (($download->passwordID > 0 && !isset($_POST['passwordEnabled'])) || (isset($_POST['passwordEnabled']) && $_POST['passwordEnabled'])) ? print 'block' : print 'none'; ?>;">
<?php
					if ($download->passwordID > 0) {
?>
						<label for="changePassword"><input class="checkbox" type="checkbox" id="changePassword" name="changePassword" onclick="showPasswordFields();" value="1" /> Set password</label><br />
<?php
					}
					else {
?>
						<input type="hidden" name="changePassword" id="changePassword" value="1" />
<?php
					}
?>
						<span id="passwordFields" style="display:<?php ($download->passwordID > 0) ? print 'none' : print 'block'; ?>;">
							<label for="downloadPassword">Password</label>
							<input type="password" id="downloadPassword" name="downloadPassword" size="32" value="" autocomplete="off" />
						</span>
					</p>

				</td>
			</tr>
<?php
	}

		if ($download->id > 0 && $adminPageAccessPermissions->updateContent || $download->id == -1 && $adminPageAccessPermissions->createContent) {
?>
			<tr>
				<td colspan="2" class="generic_finish"><em><?php print $stepCounter++; ?>.</em>
					<span>
						<input type="submit" class="btn submit" value="Save" name="saveDownload" />
					</span>
				</td>
			</tr>
<?php
		}
?>
		</table>
<?php
	if ($download->id != -1) {
?>
	<div id="fileListArea"<?php if ($numFiles < 1) print ' style="display: none;"'; ?>>
		<!-- List files -->
		<h3>File Listing: </h3>
		<table class="list_table">
			<tr>
				<th><span>Type</span></th>
				<th><span>File</span> title</th>
				<th><span>Size</span></th>
				<th><span>Filename</span> / URL</th>
				<th style="text-align: center;" colspan="2"><span>Position</span></th>
<?php
			if ($adminPageAccessPermissions->deleteContent) {
?>
				<th class="empty"></th>
<?php
			}
?>
			</tr>
		<tbody id="fileList">
<?php
		foreach ($allFiles as $index => $fileItem) {
			if ($fileItem->url == '') {
				$filename = $fileItem->filename;
				$extension = $fileItem->getFilenameExtension();
			}
			else {
				$filename = $fileItem->url;
				$extension = $fileItem->getURLExtension();
			}
?>
			<tr id="file<?php print $fileItem->id;?>">
				<td class="generic_row"><?php if ($extension != '') { ?><img class="extension_img" alt="<?php print $extension;?>" src="<?php print PROTOCOL . CC_DOMAIN . $fileItem->getFileIcon('/jadu/images/file_type_icons/'); ?>" /> <span><?php print $extension;?></span><?php } else { print '&nbsp;'; } ?></td>
<?php
			if ($adminPageAccessPermissions->updateContent) {
?>
				<td class="generic_row_link"><span><a href="./websection_downloads.php?downloadID=<?php print $download->id; ?>&amp;fileID=<?php print $fileItem->id; ?>">
				<?php print encodeHtml($fileItem->title);?></a>
                <!--include file for copy to clipboard button-->
                	<?php if (file_exists(JADU_HOME . "/custom/fordham_websection_downloads_custom.php")) {
                              include("custom/fordham_websection_downloads_custom.php");}
					 ?>
                
                </span>
             
                </td>
<?php
			}
			else {
?>
				<td class="generic_row"><span><?php print encodeHtml($fileItem->title);?></span></td>
<?php
			}
?>
				<td class="generic_row"><span><?php print $fileItem->getHumanReadableSize();?></span></td>
				<td class="generic_row"><span><?php print encodeHtml($filename);?></span></td>
				<td class="position_up">
<?php
				if ($index+1 > 1) {
?>
					<a href="<?php print modifyRequestParameters('moveFileID='.$fileItem->id.'&move=up&statusMessage=&message=&fileID=&viewFile='); ?>" title="Move up"><span>Move up</span></a>
<?php
				}
?>
				</td>
				<td class="position_down">
<?php
				if ($index+1 < $numFiles) {
?>
					<a href="<?php print modifyRequestParameters('moveFileID='.$fileItem->id.'&move=down&statusMessage=&message=&fileID=&viewFile='); ?>" title="Move down"><span>Move down</span></a>
<?php
				}
?>
				</td>
<?php
			if ($adminPageAccessPermissions->deleteContent) {
?>
				<td class="generic_row_end"><span><input class="checkbox" type="checkbox" name="deleteID[]" value="<?php print $fileItem->id;?>"></span></td>
<?php
			}
?>
			</tr>
<?php
		}
?>
		</tbody>
		<tfoot>
			<tr class="list_table_finish">
				<td class="table_finish_curve" colspan="6">
<?php
			if ($adminPageAccessPermissions->createContent) {
?>
					<input type="button" class="btn interimButton" name="uploadShow" value="Upload new file" onclick="<?php if ($file->id == -1) print 'viewFileFunction();'; else print 'clearFile();';?>">
<?php
				if (isset($excludedFieldList) && !in_array('linkShow', $excludedFieldList)) {
?>
					&nbsp;
					<input type="button" class="btn interimButton" name="linkShow" value="New Link to File" onclick="<?php if ($file->id == -1) print 'viewLinkFunction();'; else print 'clearLink();';?>">
<?php
				}
?>
					&nbsp;
					<input id="dropButton" style="display:none;" type="button" class="btn interimButton" name="dropboxShow" value="Drop in new files" onclick="$('dropbox').style.display = 'block'; return false;" >
<?php
			}
?>
				</td>

<?php
			if ($adminPageAccessPermissions->deleteContent) {
?>
				<td>
					<input type="submit" class="btn interimButton" value="Delete" name="deleteFiles" onClick="return confirmDeleteSelected('deleteID[]');">
				</td>
<?php
			}
?>
			</tr>
		</tfoot>
		</table>
		</div>
		<div id="noFilesArea" class="not_yet"<?php if ($numFiles > 0) print ' style="display: none;"'; ?>>
			<h3>There are no files assigned to this download</h3>
<?php
		if ($adminPageAccessPermissions->createContent) {
?>
			<p>
				<input type="button" class="btn interimButton" name="uploadShow" value="Upload New File" onclick="<?php if ($file->id == -1) print 'viewFileFunction();'; else print 'clearFile();';?>">
<?php
				if (isset($excludedFieldList) && !in_array('linkShow', $excludedFieldList)) {
?>
					&nbsp;
					<input type="button" class="btn interimButton" name="linkShow" value="New Link to File" onclick="<?php if ($file->id == -1) print 'viewLinkFunction();'; else print 'clearLink();';?>">
<?php
				}
?>
				<input id="dropButton" type="button" class="btn interimButton" name="dropboxShow" value="Drop in new files" onclick="$('dropbox').style.display = 'block'; return false;" >
			</p>
<?php
		}
?>
		</div>
	</form>

	<div id="fileArea" style="display: <?php if ($viewFile == 'true') print 'block'; else print 'none'; ?>;">
<?php
	if ($retail) {
?>
			<form name="fileForm" id="fileForm" action="./retail_product_details_download_detail.php?productID=<?php print intval($product->id); ?>&downloadID=<?php print $download->id; ?>" method="post" enctype="multipart/form-data">
<?php
	}
	else {
?>
			<form  name="fileForm" id="fileForm" action="./websection_downloads.php?downloadID=<?php print $download->id; ?>" method="post" enctype="multipart/form-data">
<?php
	}
?>
			<input type="hidden" name="viewFile" value="true" />
			<input type="hidden" name="fileID" value="<?php print $file->id; ?>" />
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php print MAX_UPLOAD_FILE_SIZE_BYTES; ?>" />
			<table class="generic_table">
				<tr>
					<td class="generic_desc<?php if (isset($errors['fileTitle']) && $errors['fileTitle']) { print '_error'; } ?>"><em><?php $stepCounter = 1; print $stepCounter++; ?>.</em> <p>Title*</p></td>
					<td class="generic_action"><input type="text" name="fileTitle" id="fileTitle" size="42" value="<?php print encodeHtml($file->title); ?>"></td>
				</tr>
<?php
				if ($file->id != -1) {
?>
				<tr id="change_file">
					<td class="generic_desc"><em><?php print $stepCounter++; ?>.</em> <p>Change File?</p></td>
					<td class="generic_action"><input type="checkbox" name="changeFile" id="changeFile" onclick="showFileUploadRow('');"<?php print $file->id != -1 && (!isset($_POST['saveFile']) || (!isset($errors['downloadFileBrowse']) && !isset($errors['webdavFile']))) ? '' : ' checked="checked"'; ?> /></td>
				</tr>
<?php
				}
?>
				<tr id="upload_row"<?php print $file->id != -1 && (!isset($_POST['saveFile']) || (!isset($errors['downloadFileBrowse']) && !isset($errors['webdavFile']))) ? ' style="display:none;"' : ''; ?>>
					<td class="generic_desc<?php if (isset($errors['downloadFileBrowse']) || isset($errors['webdavFile'])) { print '_error'; } ?>"><em><?php print $stepCounter++; ?>.</em> <p>File selection<?php if (!isset($_GET['fileID']) ) { print "*"; } ?></p></td>
					<td class="generic_action">
<?php
					if (INSTALLATION_TYPE === GALAXY || SERVER_SOFTWARE === IIS) {
?>
						<input type="hidden" name="upload_method" id="upload_method" value="upload" />
						<div id="download_upload_file">
							<input type="file" name="downloadFileBrowse" size="48" value="" />
							<p>The maximum allowed file size is: <?php print formatFilesize(MAX_UPLOAD_FILE_SIZE_BYTES); ?></p>
						</div>
<?php
					}
					else {
?>
						<script type="text/javascript">
							function displayUploadMethod(element) {
								if (element.selectedIndex == 1) {
									document.getElementById('download_upload_file').style.display = '';
									document.getElementById('download_webdav_file').style.display = 'none';
								}
								else if (element.selectedIndex == 2) {
									document.getElementById('download_webdav_file').style.display = '';
									document.getElementById('download_upload_file').style.display = 'none';
								}
								else {
									document.getElementById('download_upload_file').style.display = 'none';
									document.getElementById('download_webdav_file').style.display = 'none';
								}
							}
						</script>
						<select name="upload_method" id="upload_method" onchange="displayUploadMethod(this);">
							<option value="">Select an upload method</option>
							<option value="upload"<?php print isset($_POST['upload_method']) && $_POST['upload_method'] == 'upload'?' selected="selected"':''; ?>>Upload a new file</option>
							<option value="webdav"<?php print isset($_POST['upload_method']) && $_POST['upload_method'] == 'webdav'?' selected="selected"':''; ?>>Select a file from the bulk upload list</option>
						</select><br />
						<br style="clear:both" />
						<div id="download_upload_file"<?php if (!isset($_POST['upload_method']) || $_POST['upload_method'] != 'upload') { print ' style="display:none;"'; } ?>>
							<input type="file" name="downloadFileBrowse" size="48" value="" />
							<p>The maximum allowed file size is: <?php print formatFilesize(MAX_UPLOAD_FILE_SIZE_BYTES); ?></p>
						</div>
						<div id="download_webdav_file"<?php if (!isset($_POST['upload_method']) || $_POST['upload_method'] != 'webdav') { print ' style="display:none;"'; } ?>>
							<input type="hidden" name="webdavFile" id="webdavFile" value="" />
							<input type="hidden" name="webdavFileFilter" id="webdavFileFilter" value="" />
							<input type="button" class="btn interimButton" id="selectFile" value="Select File from Bulk Uploads" onclick="return loadLightbox('webdav_uploads', 'lb', 'type=downloads&filter=admin');">
						</div>
<?php
					}
?>
					</td>
				</tr>
<?php
			if (($file->id > 0 && $adminPageAccessPermissions->updateContent) || ($file->id < 1 && $adminPageAccessPermissions->createContent)) {
?>
				<tr>
					<td colspan="2" class="generic_finish">
						<em><?php print $stepCounter++; ?>.</em>
						<span><input type="submit" class="btn submit" value="Save" name="saveFile" /></span>
					</td>
				</tr>
<?php
			}

			if ($file->id > 0 && $adminPageAccessPermissions->deleteContent) {
?>
				<tr>
					<td class="generic_delete" colspan="2"><span><input type="submit" class="btn interimButton" value="Delete File" name="deleteFile" onclick="return confirmSubmit();" /></span></td>
				</tr>
<?php
			}
?>
			</table>
		</form>
	</div>

	<div id="linkArea" style="display: <?php if ($viewLink == 'true') print 'block'; else print 'none'; ?>;">
		<form name="linkForm" id="linkForm" action="./websection_downloads.php?downloadID=<?php print $download->id; ?>" method="post" enctype="multipart/form-data">
			<input type="hidden" name="viewLink" value="true" />
			<input type="hidden" name="fileID" value="<?php print $file->id; ?>" />
			<table class="generic_table">
				<tr>
					<td class="generic_desc<?php if (!empty($errors['linkTitle'])) { print '_error'; } ?>"><em><?php $stepCounter = 1; print $stepCounter++; ?>.</em> <p>Title</p></td>
					<td class="generic_action"><input type="text" name="linkTitle" size="43" value="<?php print encodeHtml($file->title); ?>" /></td>
				</tr>
				<tr>
					<td class="generic_desc<?php if (!empty($errors['url'])) { print '_error'; } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Target File URL</p><p class="generic_desc_text">(e.g. http://www.example.com/file.pdf)</p></td>
					<td class="generic_action"><input type="text" name="url" size="43" value="<?php print encodeHtml($file->url); ?>" /></td>
				</tr>
				<tr>
					<td class="generic_desc<?php if (!empty($errors['size'])) { print '_error'; } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Target File size</p><p class="generic_desc_text">(Numerical only)</p></td>
					<td class="generic_action">
						<input type="text" name="size" size="28" value="<?php print $file->getHumanReadableSizeNoExtension(); ?>" />&nbsp;
						<select class="select" name="selectSize" size="1">
<?php
						foreach ($FILE_SIZES as $sizeExtension) {
?>
							<option value="<?php print $sizeExtension; ?>"<?php if (mb_strpos($file->getHumanReadableSize(), $sizeExtension) !== false) print ' selected="selected"';?>><?php print $sizeExtension; ?></option>
<?php
						}
?>
						</select>
					</td>
				</tr>
<?php
			if (($file->id > 0 && $adminPageAccessPermissions->updateContent) || ($file->id < 1 && $adminPageAccessPermissions->createContent)) {
?>
				<tr>
					<td colspan="2" class="generic_finish"><em><?php print $stepCounter++; ?>.</em> <span><input type="submit" class="btn submit" value="Save" name="saveLink" /></span></td>
				</tr>
<?php
			}

			if ($file->id > 0 && $adminPageAccessPermissions->deleteContent) {
?>
				<tr>
					<td class="generic_delete" colspan="2"><span><input type="submit" class="btn interimButton" value="Delete Link" name="deleteFile" onclick="return confirmSubmit();" /></span></td>
				</tr>
<?php
			}
?>
			</table>
		</form>
	</div>

	<form name="dropForm" id="dropForm" action="./websection_downloads.php?downloadID=<?php print $download->id; ?>" method="post" enctype="multipart/form-data">
		<div id="dropbox" style="display: none;">
			<p>Drop your files here</p>
			<input type="file" id="fileElem" multiple="multiple" style="display: none;" />
		</div>
	</form>

	<script type="text/javascript">
			var draggable = false;
			if('draggable' in document.createElement('span')) {
				draggable = true;
			}

			if (window.FormData && window.File && draggable) {
				document.getElementById('dropButton').style.display = 'inline';
			}

			function handleFiles (files) {
				if (files.length > 0) {
					for ( var i = 0; i < files.length; i++) {
						var file = files[i];
						fileUpload(file);
					}
				}
			}

			function fileUpload (file) {
				var formData = new FormData();
				formData.append('uploaded_file', file);

				var o = document.getElementById("dropbox");
				var progress = o.appendChild(document.createElement("p"));
				var counter = o.appendChild(document.createElement("span"));

				progress.appendChild(document.createTextNode(file.name));
				progress.appendChild(counter);

				var xhr = new XMLHttpRequest();
				xhr.open("POST", 'uploadFileFromDragDrop.php?downloadID=<?php print $download->id;?>');

				xhr.upload.addEventListener("progress", function(e) {
					var pc = parseInt((e.loaded / e.total * 100));
					counter.innerHTML = pc + "%";
				}, false);

				xhr.onreadystatechange = function () {
					if (xhr.readyState == 4 && xhr.status == 200) {
						if (xhr.responseText.indexOf('rror') < 0) {
							counter.innerHTML = "100%";
							counter.className = 'uploadsuccess';
							addRow(file, xhr.responseText);
						}
						else {
							counter.innerHTML = xhr.responseText;
							counter.className = 'uploadfailed';
						}
					}
				}
				xhr.send(formData);
			}

			var dropbox = document.getElementById('dropbox');

			if (window['addEventListener']) {
				dropbox.addEventListener('dragenter', dragenter, false);
				dropbox.addEventListener('dragover', dragover, false);
				dropbox.addEventListener('drop', drop, false);
			}

			function dragenter (e) {
				e.stopPropagation();
				e.preventDefault();
			}

			function dragover (e) {
				e.stopPropagation();
				e.preventDefault();
			}

			function drop (e) {
				e.stopPropagation();
				e.preventDefault();

				var dt = e.dataTransfer;
				var files = dt.files;

				handleFiles(files);
			}

			function addRow(file, id) {

				if (document.getElementById('noFilesArea').style.display == '') {
					document.getElementById('noFilesArea').style.display = 'none';
					document.getElementById('fileListArea').style.display = '';
				}
				var tbody = document.getElementById('fileList');

				var tr = document.createElement('tr');
				tr.id = 'file'+id;
				tbody.appendChild(tr);

				var prevTr = tr.previous('tr');

				var td = document.createElement('td');
				var td2 = document.createElement('td');
				var td3 = document.createElement('td');
				var td4 = document.createElement('td');
				var td5 = document.createElement('td');
				var td6 = document.createElement('td');

				tr.appendChild(td);
				tr.appendChild(td2);
				tr.appendChild(td3);
				tr.appendChild(td4);
				tr.appendChild(td5);
				tr.appendChild(td6);

				td.className = 'generic_row';
				td3.className = 'generic_row';
				td4.className = 'generic_row';
				td5.className = 'position_up';
				td6.className = 'position_down';

				var ext = file.name.split('.');

				if (ext.length > 1) {
					ext = ext[ext.length-1].toUpperCase();

					var img = document.createElement('img');
					img.className = 'extension_img';
					img.alt = ext;
					img.src = '<?php print PROTOCOL . CC_DOMAIN ; ?>'+ '/jadu/images/file_type_icons/' + ext + '.gif';

					var extspan = document.createElement('span');
					extspan.innerHTML = ext;

					td.appendChild(img);
					td.appendChild(extspan);
				}

<?php
			if ($adminPageAccessPermissions->updateContent) {
?>
				td2.className = 'generic_row_link';
				var span = document.createElement('a');
				span.href = './websection_downloads.php?downloadID=<?php print $download->id; ?>&fileID='+id;
<?php
			}
			else {
?>
				td2.className = 'generic_row';
				var span = document.createElement('span');
<?php
			}
?>

				var span2 = document.createElement('span');
				var span3 = document.createElement('span');

				span.innerHTML = file.name;
				span2.innerHTML = (file.size/1000) + ' KB';
				span3.innerHTML = file.name;

				td2.appendChild(span);
				td3.appendChild(span2);
				td4.appendChild(span3);

<?php
			if ($adminPageAccessPermissions->updateContent) {
?>
				if (typeof prevTr != 'undefined') {
					//'move up' arrow
					var upArrowAnchor = document.createElement('a');
					upArrowAnchor.href="<?php print SECURE_JADU_PATH . '/websections/websection_downloads.php?downloadID='.intval($_GET['downloadID']);?>&moveFileID="+id+"&move=up";
					upArrowAnchor.title="Move up";
					var upArrowSpan = document.createElement('span');
					upArrowSpan.innerHTML = "Move up";
					upArrowAnchor.appendChild(upArrowSpan);
					td5.appendChild(upArrowAnchor);

					//'move down' arrow for previous row
					if (prevTr.children(5) !== null) {
						if (prevTr.children(5).className=='position_down' || prevTr.children(5).className=='generic_row') {
							prevTr.children(5).className='position_down';
							var downArrowAnchor = document.createElement('a');
							var prevId = prevTr.id.replace("file", "");
							downArrowAnchor.href="<?php print SECURE_JADU_PATH . '/websections/websection_downloads.php?downloadID='.intval($_GET['downloadID']);?>&moveFileID="+prevId+"&move=down";
							downArrowAnchor.title="Move down";
							var downArrowSpan = document.createElement('span');
							downArrowSpan.innerHTML = "Move down";
							downArrowAnchor.appendChild(downArrowSpan);
							prevTr.children(5).appendChild(downArrowAnchor);
						}
					}
				}
<?php
			}
			if ($adminPageAccessPermissions->deleteContent) {
?>
				var td7 = document.createElement('td');
				tr.appendChild(td7);
				td7.className = 'generic_row_end';

				var span4 = document.createElement('span');
				td7.appendChild(span4);

				var input = document.createElement('input');
				input.className = 'checkbox';
				input.name = 'deleteID[]';
				input.value = id;
				input.type = 'checkbox';

				span4.appendChild(input);
<?php
			}
?>
			}

	</script>
<?php
	}
	}
	include("../includes/footer.php");
?>
