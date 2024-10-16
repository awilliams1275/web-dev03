<?php

        // Disallow direct access to this script. Should be include only
        if ($_SERVER['PHP_SELF'] == '/jadu/websections/' . __FILE__) {
                exit;
        }

        include_once('websections/JaduPageSupplementLocations.php');
        $locations = getAllSupplementLocations();
        
        include_once('websections/JaduPageSupplementWidgetPublicCode.php');
        include_once($widget->classFile);
        
        if ($supplementRecordID > 0) {
                $CuraPersonalisSupplement = getCustomCuraPersonalisSupplement($supplementRecordID);
        }
        else {
                $CuraPersonalisSupplement = new CustomCuraPersonalisSupplement();
        }
        
        if (isset($_POST['save'])) {
                $CuraPersonalisSupplement->title = isset($_POST['title']) ? $_POST['title'] : $CuraPersonalisSupplement->title;
                $CuraPersonalisSupplement->fu_cura_text = isset($_POST['fu_cura_text']) ? $_POST['fu_cura_text'] : $CuraPersonalisSupplement->fu_cura_text;
				$CuraPersonalisSupplement->fu_cura_by = isset($_POST['fu_cura_by']) ? $_POST['fu_cura_by'] : $CuraPersonalisSupplement->fu_cura_by;
				$CuraPersonalisSupplement->fu_cura_story = isset($_POST['fu_cura_story']) ? $_POST['fu_cura_story'] : checkURLForHTTP($CuraPersonalisSupplement->fu_cura_story);
                $CuraPersonalisSupplement->fu_cura_url = isset($_POST['fu_cura_url']) ? $_POST['fu_cura_url'] : $CuraPersonalisSupplement->fu_cura_url;

                // Validate the supplement
                $errors = validateCustomCuraPersonalisSupplement($CuraPersonalisSupplement);
                
                if ($editType == 'supplement' || $_GET['contentType'] != '') {
                        // Get the location and validate it, only for supplement editing though
                        $locationOnPage = isset($_POST['locationOnPage']) ? strtolower($_POST['locationOnPage']) : '';
                        $locationIsValid = false;
                        if ($locationOnPage != '') {
                                foreach ($locations as $location) {
                                        if (strtolower($location->title) == $locationOnPage) {
                                                $locationIsValid = true;
                                        }
                                }
                        }
                
                        if (!$locationIsValid) {
                                $errors['locationOnPage'] = true;
                        }
                }
                
                if (count($errors) == 0) {
                        if ($CuraPersonalisSupplement->id < 1) {
                                $CuraPersonalisSupplement->id = newCustomCuraPersonalisSupplement($CuraPersonalisSupplement);
                        }
                        else {
                                updateCustomCuraPersonalisSupplement($CuraPersonalisSupplement);
                        }
                        
                        deleteAllCategoriesForSupplement($CuraPersonalisSupplement->id);

                        $supplementCategory = new SupplementCategory();
                        $supplementCategory->categoryID = getFirstCategoryIDForItemOfType ($contentTypeCatTable, ($contentType == 'document')?$documentID:$itemID);
                        $supplementCategory->supplementRecordID = $CuraPersonalisSupplement->id;
                        $supplementCategory->supplementWidgetID = $widget->id;
                        newSupplementCategory($supplementCategory);
                        
                        if ($editType == 'supplement' || $_GET['contentType'] != '') {
                                if ($pageSupplement->id > 0) {
                                        if ($pageSupplement->locationOnPage != $locationOnPage) {
                                                // Get the new position for the new location
                                                $pageSupplement->position = getMaxPageSupplementPosition($pageSupplement->contentType, $pageSupplement->itemID, $locationOnPage);

                                                // Reorder the remaining supplements at the old location
                                                fixPageSupplementPositions($pageSupplement->contentType, $pageSupplement->itemID);

                                                $pageSupplement->locationOnPage = $locationOnPage;
                                        }
                                        $pageSupplement->supplementRecordID = $CuraPersonalisSupplement->id;
                                        updatePageSupplement($pageSupplement);
                                }
                                else {
                                        $pageSupplement = new PageSupplement();
                                        $pageSupplement->contentType = $contentType;
                                        $pageSupplement->itemID = $itemID;
                                        $pageSupplement->supplementWidgetID = $widget->id;
                                        $pageSupplement->supplementRecordID = $CuraPersonalisSupplement->id;
                                        $pageSupplement->locationOnPage = $locationOnPage;
                                        $pageSupplement->id = newPageSupplement($pageSupplement);
                                }
                                
                                header('Location: supplement_list.php?contentType=' . $pageSupplement->contentType . '&itemID=' . $pageSupplement->itemID . '&location=' . $pageSupplement->locationOnPage . '&statusMessage=' . urlencode('Supplement saved'));
                                exit;
                        }
                        else {
                                header('Location: supplement_record_list.php?widgetID=' . $widget->id . '&statusMessage=' . urlencode('Supplement saved'));
                                exit;
                        }
                }
        }
        
        $supplementRecords = getAllCustomCuraPersonalisSupplements(array());
        $imageDirectory = $SECURE_SERVER . '/images/';

        // The start of the table is output before this script is included so error message is always output but hidden
        if (count($errors) > 0) {
?>
        <script type="text/javascript">
                $('validate_mssg').show();
        </script>
<?php
        }

        if ($editType == 'supplement' || $_GET['contentType'] != '') {
?>
                <tr>
                        <td class="generic_desc"><em><?php print $stepCounter++; ?>.</em> <p>Select existing</p></td>
                        <td class="generic_action">
                                <select class="select" onchange="selectNav(this);">
                                        <option value="supplement_details.php?supplementRecordID=-1&amp;supplementID=-1&amp;widgetID=<?php print (int) $widget->id; ?>&amp;contentType=<?php print encodeHtml($contentType); ?>&amp;itemID=<?php print (int) $itemID; ?>&amp;location=<?php print encodeHtml($locationOnPage); ?>">Create new supplement</option>
<?php
                                foreach ($supplementRecords as $supplementRecord) {
                                        if (adminCanAccessSupplement($supplementRecord->id, $adminID)) {
                                                $selected = '';
                                                if ($supplementRecordID == $supplementRecord->id) {
                                                        $selected = ' selected="selected"';
                                                }
?>
             <option value="supplement_details.php?supplementRecordID=<?php print (int) $supplementRecord->id; ?>&amp;supplementID=<?php print (int) $pageSupplement->id; ?>&amp;widgetID=<?php print (int) $widget->id; ?>&amp;contentType=<?php print encodeHtml($contentType); ?>&amp;itemID=<?php print (int) $itemID; ?>&amp;location=<?php print encodeHtml($locationOnPage); ?>"<?php print $selected; ?>><?php print encodeHtml($supplementRecord->title); ?></option>
<?php
                                        }
                                }
?>
                                </select>
                        </td>
                </tr>
                <tr>
                        <td class="generic_desc<?php if (isset($errors['locationOnPage'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Placement</p></td>
                        <td class="generic_action">
                                <select class="select" name="locationOnPage">
<?php
                                foreach ($locations as $locationItem) {
                                        $publicCode = getSupplementPublicCode($widget->id, $locationItem->title);
                                        if ($publicCode->id == -1) {
                                                continue;
                                        }
                                
                                        $selected = '';
                                        if ((isset($_POST['save']) && $locationOnPage == strtolower($locationItem->title)) || 
                                                ($pageSupplement->locationOnPage == strtolower($locationItem->title))) {
                                                $selected = ' selected="selected"';
                                        }
                                        else if ($location == strtolower($locationItem->title)) {
                                                $selected = ' selected="selected"';
                                        }
?>
    <option value="<?php print encodeHtml($locationItem->title); ?>"<?php print $selected; ?>><?php print encodeHtml($locationItem->title); ?></option>
<?php
                                }
?>
                                </select>
                        </td>
                </tr>
<?php
        }
?>
                <tr>
                        <td class="generic_desc<?php if (isset($errors['title'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Title*</p></td>
                        <td class="generic_action">
                                <input type="text" name="title" value="<?php print encodeHtml($CuraPersonalisSupplement->title); ?>" class="field" />
                        </td>
                </tr>

                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_cura_text'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Text*</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_cura_text" value="<?php print encodeHtml($CuraPersonalisSupplement->fu_cura_text); ?>" class="field" />
                        </td>
                </tr>
                

                <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_cura_by'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Byline*</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_cura_by" value="<?php print encodeHtml($CuraPersonalisSupplement->fu_cura_by); ?>" class="field"  size="50" />
                        </td>
                </tr>
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_cura_story'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Submit Link Text</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_cura_story" value="<?php print encodeHtml($CuraPersonalisSupplement->fu_cura_story); ?>" class="field"  size="50" />
                        </td>
                </tr>
                
				
				<tr>
                        <td class="generic_desc<?php if (isset($errors['fu_cura_url'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Submit URL</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_cura_url" value="<?php print encodeHtml($CuraPersonalisSupplement->fu_cura_url); ?>" class="field"  size="50" />
                        </td>
                </tr>
               
   
                
                
                <!--Submit-->
                 <tr>
                        <td colspan="2" class="generic_finish"><em><?php print $stepCounter++; ?>.</em>
                                <span><input type="submit" class="button" name="save" value="Save Supplement"/></span> 
                        </td>
                </tr>

