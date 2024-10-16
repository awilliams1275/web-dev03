<?php
	require_once('../includes/session_header.php');
	include_once('JaduConstants.php');
	include_once('utilities/JaduAdministrators.php');
	include_once('utilities/JaduAdminTasks.php');
	include_once('workflow/JaduWorkflowAdminLevels.php');
	include_once('workflow/JaduWorkflowAdminGroups.php');
	include_once('workflow/JaduWorkflowAdminsToGroups.php');		
	include_once('workflow/JaduWorkflowWorkflows.php');
	include_once('workflow/JaduWorkflowWorkflowsToContent.php');
	include_once('workflow/JaduWorkflowWorkflowsToGroups.php');	
	include_once('workflow/JaduWorkflowWorkflowsToLevels.php');	
	include_once('workflow/JaduWorkflowWorkflowsToAdministrators.php');
	include_once('workflow/JaduWorkflowWorkflowAlerts.php');
	include_once('workflow/JaduWorkflowWorkflowEscalations.php');
	include_once('workflow/JaduWorkflowWorkflowAlertsToAdministrators.php');
	include_once('workflow/JaduWorkflowWorkflowEscalationsToAdministrators.php');

	$DEFAULT_WORKFLOW = true;
	$COLLABORATION_ADMINS = array();
	$PROOFING_ADMINS = array();
	$ADMIN_MUST_APPROVE = false;
	$THIS_WORKFLOW = new Workflow();
	$THIS_WORKFLOW->id = -1;
	$thisWorkflowToAdmin = null;
	$nextWorkflowAdminLevel = getMostAuthoritativeWorkflowAdminLevel();

	/**
	 * Get the module page id using the page url and check that
	 * workflow should be applied to it (using the 'workflow' field).
	 *
	 * If the page has a parent then get the parent and use that instead.
	 *
	 * If workflow should not be applied to the module page then return -1 
	 * so that we know not to apply any workflow rules to this request.
	 */
	function getWorkflowModulePageID() {

		$currentModulePage = getModulePageFromURL(
			substr(
				$_SERVER['PHP_SELF'], 
				strpos($_SERVER['PHP_SELF'], '/jadu') + 5
			)
		);

		if ($currentModulePage->workflow) {
			return $currentModulePage->id;
		}
		else if ($currentModulePage->parent_id != -1) {
			$parentModulePage = getModulePage($currentModulePage->parent_id);
			if ($parentModulePage->workflow) {
				return $parentModulePage->id;
			}
		}

		return -1;
	}

	/**
	 * Function used to set the global Workflow variables
	 * 
	 * @global type $DEFAULT_WORKFLOW
	 * @global type $COLLABORATION_ADMINS
	 * @global type $PROOFING_ADMINS
	 * @global type $ADMIN_MUST_APPROVE
	 * @global type $THIS_WORKFLOW
	 * @global type $thisWorkflowToAdmin
	 * @global type $nextWorkflowAdminLevel
	 * @param type $adminID The id of the currently logged in Administrator
	 * @param AdminTask $task An "Awaiting Approval" AdminTask for the Content
	 * @param array[int]string $itemCategoryIDs An optional array of categoryID values used to 
	 *        set the Workflow based on Navigation Categories that match for the Workflow 
	 *        Privilege Group and Content.
	 * @return boolean False on failure
	 */
	function initWorkflowGlobals($adminID, AdminTask $task, array $itemCategoryIDs = array()) {

		global $DEFAULT_WORKFLOW, $COLLABORATION_ADMINS, $PROOFING_ADMINS, $ADMIN_MUST_APPROVE, $THIS_WORKFLOW, $thisWorkflowToAdmin, $nextWorkflowAdminLevel;

		$DEFAULT_WORKFLOW = true;
		$COLLABORATION_ADMINS = array();
		$PROOFING_ADMINS = array();
		$ADMIN_MUST_APPROVE = false;
		$THIS_WORKFLOW = new Workflow();
		$thisWorkflowToAdmin = null;

		// Only continue if Advanced Workflow is enabled
		if (!ADVANCED_WORKFLOW_MODE || !is_numeric($adminID) || $adminID < 1 ) {
			return false;
		}

		$workflowModulePageID = getWorkflowModulePageID();

		if (!is_numeric($workflowModulePageID) || $workflowModulePageID < 1) {
			return false;
		}

		$workflowToAdmins = getAllWorkflowsToAdministratorsForAdministrator($adminID);
		$itemWorkflows = array();

		/**
		 * If an Admin Task exists for the Content maintain the originating 
		 * workflow.
		 * 
		 * If an administrator is part of a live workflow then set the 
		 * workflow as active ($THIS_WORKFLOW) and flag that we're not
		 * using the default workflow.
		 *
		 * The first workflow found that an admin is in is selected.
		 */
		if ($task->id > 0) {
			$workflow = getWorkflow($task->workflowID);

			if ($workflow->live) {
				foreach ($workflowToAdmins as $workflowToAdmin) {
					if ($workflowToAdmin->workflowID == $workflow->id) {
						// Set first matching Workflow only
						$DEFAULT_WORKFLOW = false;
						$THIS_WORKFLOW = $workflow;
						$thisWorkflowToAdmin = $workflowToAdmin;
						break;
					}
				}
			}
		}
		else {
			// Get all of the workflows that have been applied to the module page.
			$workflowsToContent = getAllWorkflowToContentForContent($workflowModulePageID);
			foreach ($workflowsToContent as $workflowToContent) {
				$workflow = getWorkflow($workflowToContent->workflowID);

				if ($workflow->live) {
					foreach ($workflowToAdmins as $workflowToAdmin) {
						if ($workflowToAdmin->workflowID == $workflow->id) {
							// Set first matching Workflow only
							if ($THIS_WORKFLOW->id == -1) {
								$DEFAULT_WORKFLOW = false;
								$THIS_WORKFLOW = $workflow;
								$thisWorkflowToAdmin = $workflowToAdmin;
							}

							// Build up an array of Workflows matching both the Admin and Content
							if (!in_array($workflowToAdmin, $itemWorkflows)) {
								$itemWorkflows[] = $workflow;
							}
						}
					}
				}
			}

			// Recalculate Workflow if it must match a Navigation Category
			if (!empty($itemCategoryIDs)) {
				include_once('workflow/JaduWorkflowAssignedCategories.php');

				$workflowsToGroups = array();
				$allWorkflowGroupIDs = array();
				$contentWorkflowsForAdminToCategoryIDs = array();
				$categoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);

				foreach ($itemWorkflows as $workflow) {
					$workflowsToGroups = getAllWorkflowsToGroupsForWorkflow($workflow->id);
					foreach ($workflowsToGroups as $workflowToGroup) {
						// Exclude workflow groups we have already checked since they define the categories and we only consider the first matching workflow
						if (in_array($workflowToGroup->groupID, $allWorkflowGroupIDs)) {
							continue;
						}
						$allWorkflowGroupIDs[] = $workflowToGroup->groupID;

						// Get all navigation categories for the privilege group
						$allWorkflowCategories = getAllWorkflowCategories($workflowToGroup->groupID, WORKFLOW_ADMIN_GROUP_TABLE, BESPOKE_CATEGORY_LIST_NAME);

						// Build mapping of Workflow to Workflow Navigation Categories
						foreach ($allWorkflowCategories as $workflowCategory) {
							if (!array_key_exists($workflowToGroup->workflowID, $contentWorkflowsForAdminToCategoryIDs) || 
								!in_array($workflowCategory->categoryID, $contentWorkflowsForAdminToCategoryIDs[$workflowToGroup->workflowID])) {
								$contentWorkflowsForAdminToCategoryIDs[$workflowToGroup->workflowID][] = $workflowCategory->categoryID;
							}

							// If not inheriting skip sub-categories
							if ($workflowCategory->inherit != 1) {
								continue;
							}

							// Get all sub-categories if inherit is true
							$workflowChildCategories = $categoryList->getChildCategories($workflowCategory->categoryID);
							if (empty($workflowChildCategories)) {
								continue;
							}

							foreach ($workflowChildCategories as $workflowChildCategory) {
								if (!array_key_exists($workflowToGroup->workflowID, $contentWorkflowsForAdminToCategoryIDs) || 
									!in_array($workflowChildCategory->id, $contentWorkflowsForAdminToCategoryIDs[$workflowToGroup->workflowID])) {
									$contentWorkflowsForAdminToCategoryIDs[$workflowToGroup->workflowID][] = $workflowChildCategory->id;
								}
							}
						}
					}
				}

				if (!empty($contentWorkflowsForAdminToCategoryIDs)) {
					$workflowToAdmins = getAllWorkflowsToAdministratorsForAdministrator($adminID);
					foreach ($contentWorkflowsForAdminToCategoryIDs as $workflowID => $contentWorkflowForAdminToCategoryIDs) {
						foreach ($contentWorkflowForAdminToCategoryIDs as $categoryID) {
							// Set the first Workflow where a privilege group category matches a content item's category
							if (in_array($categoryID, $itemCategoryIDs)) {
								foreach ($workflowToAdmins as $workflowToAdmin) {
									if ($workflowToAdmin->workflowID == $workflowID) {
										$THIS_WORKFLOW = getWorkflow($workflowID);
										$thisWorkflowToAdmin = $workflowToAdmin;
										// Workflow selected
										break(3);
									}
								}
							}
						}
					}
				}
			}
		}

		// If we are not using the Standard Workflow
		if (!$DEFAULT_WORKFLOW && !is_null($thisWorkflowToAdmin)) {
			/**
			 * Get all of the administrators at the same level as the 
			 * admin we're logged in as. These are the available
			 * collaborators.
			 */
			$currentWorkflowToLevel = getWorkflowToLevel($thisWorkflowToAdmin->workflowToLevelID);
			$currentWorkflowToAdmins = getAllWorkflowsToAdministratorsInWorkflowToLevel($currentWorkflowToLevel->id);
			foreach ($currentWorkflowToAdmins as $currentWorkflowToAdmin) {
				if ($currentWorkflowToAdmin->adminID != $adminID) {
					$COLLABORATION_ADMINS[] = Jadu_Service_Container::getInstance()->getJaduAdministrator()->getAdministrator($currentWorkflowToAdmin->adminID);
				}
			}

			/**
			 * See what level we are at in the workflow. If we're at the 
			 * last level then not much else to do.
			 *
			 * If we're below the last level then get all of the levels and
			 * find out which is the one next to the one we're on.
			 *
			 * For this next level get all of the administrators in it.
			 * These will be the administrators that can approve at the 
			 * next level.
			 */
			if (isWorkflowToLevelPositionedTopInWorkflow($currentWorkflowToLevel)) {
				$nextWorkflowAdminLevel = getWorkflowAdminLevel($currentWorkflowToLevel->levelID);
				$ADMIN_MUST_APPROVE = true;
			}
			else {
				$nextWorkflowAdminLevel = getMostAuthoritativeWorkflowAdminLevel();
				$workflowToLevels = getAllWorkflowsToLevelsForWorkflow($THIS_WORKFLOW->id);
				foreach ($workflowToLevels as $i => $workflowToLevel) {
					if ($workflowToLevel->id == $currentWorkflowToLevel->id && $i+1 < count($workflowToLevels)) {
						$nextWorkflowToLevel = $workflowToLevels[($i+1)];
						$nextWorkflowAdminLevel = getWorkflowAdminLevel($nextWorkflowToLevel->levelID);
						break;
					}
				}

				if (isset($nextWorkflowToLevel)) {
					$nextWorkflowToAdmins = getAllWorkflowsToAdministratorsInWorkflowToLevel($nextWorkflowToLevel->id);
					foreach ($nextWorkflowToAdmins as $nextWorkflowToAdmin) {
						$PROOFING_ADMINS[] = Jadu_Service_Container::getInstance()->getJaduAdministrator()->getAdministrator($nextWorkflowToAdmin->adminID);
					}
				}
			}
		}
	}
	
	/**
	 * Can the current admin approve this task
	 * @param AdminTask $task An "Awaiting Approval" AdminTask for the Content
	 * @return bool Whether the admin can approve the task
	*/
	function canAdminApproveTask($admin, $task, $workflow)
	{
	
		if ($task->adminID == Jadu_Service_Container::getInstance()->getJaduAdministrator()->getCurrentAdminID()) {
			// task has been sent directly to admin
			return true;
		} else {
			if ($workflow->id > 0) {
				if ((Jadu_Service_Container::getInstance()->getJaduAdministrator()->getCurrentAdmin()->adminLevelID == $task->adminLevelID) && $task->adminID == 0) {
					$workflowToLevels = getAllWorkflowsToLevelsForWorkflow($workflow->id);
					$containsDuplicateLevels = false;
					$levelsInWorkflow = array();
					
					foreach ($workflowToLevels as $WtL) {
						if (in_array($WtL->levelID, $levelsInWorkflow)) {
							$containsDuplicateLevels = true;
						}
						$levelsInWorkflow[] = $WtL->levelID;	
					}
					
					if ($containsDuplicateLevels) {
						// if the workflow contains duplicate levels, we can't rely on task->adminLevelID
						// need to workout which level sent the item through in order to get the check
						// if this admin is the next level fromAdminID					
						$workflowToLevel = getWorkflowToLevelIDForAdminInWorkflow($task->fromAdminID, $workflow->id);
						$sentFromLevel = getWorkflowToLevel($workflowToLevel);
						
						$approvalLevelIndex = $sentFromLevel->workflowPosition + 1;
						if ($approvalLevelIndex < sizeof($levelsInWorkflow)) {
							$approvalLevel = $workflowToLevels[$approvalLevelIndex];
							$approvalAdmins = getAllWorkflowsToAdministratorsInWorkflowToLevel($approvalLevel->id);

							foreach ($approvalAdmins as $approver) {
								if ($approver->adminID == Jadu_Service_Container::getInstance()->getJaduAdministrator()->getCurrentAdminID()) {
									return true;
								}
							}
							return false;
						} else {
							return true;
						}					
					} else {
						return true;
					}
				} else {
					return false;
				}
			} else {
				// standard workflow, is the admin of the right level
				return ((Jadu_Service_Container::getInstance()->getJaduAdministrator()->getCurrentAdmin()->adminLevelID == $task->adminLevelID) && $task->adminID == 0);
			}
		}
	}
