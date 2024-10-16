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
                $adSpaceSupplement = getCustomAdSpaceSupplement($supplementRecordID);
        }
        else {
                $adSpaceSupplement = new CustomAdSpaceSupplement();
        }
        
        if (isset($_POST['save'])) {
                $adSpaceSupplement->title = isset($_POST['title']) ? $_POST['title'] : $adSpaceSupplement->title;
                $adSpaceSupplement->fu_ad_title1 = isset($_POST['fu_ad_title1']) ? $_POST['fu_ad_title1'] : $adSpaceSupplement->fu_ad_title1;
                $adSpaceSupplement->fu_ad_title2 = isset($_POST['fu_ad_title2']) ? $_POST['fu_ad_title2'] : $adSpaceSupplement->fu_ad_title2;
                $adSpaceSupplement->fu_ad_title_link = isset($_POST['fu_ad_title_link']) ? $_POST['fu_ad_title_link'] : $adSpaceSupplement->fu_ad_title_link;
                $adSpaceSupplement->fu_ad_src1a = isset($_POST['imageURL']) ? $_POST['imageURL'] : $adSpaceSupplement->fu_ad_src1a;
                $adSpaceSupplement->fu_ad_head = isset($_POST['fu_ad_head']) ? $_POST['fu_ad_head'] : $adSpaceSupplement->fu_ad_head;
                $adSpaceSupplement->fu_ad_text = isset($_POST['fu_ad_text']) ? $_POST['fu_ad_text'] : $adSpaceSupplement->fu_ad_text;
                $adSpaceSupplement->fu_ad_tag = isset($_POST['fu_ad_tag']) ? $_POST['fu_ad_tag'] : $adSpaceSupplement->fu_ad_tag;
                $adSpaceSupplement->fu_ad_tag_url = isset($_POST['fu_ad_tag_url']) ? $_POST['fu_ad_tag_url'] : $adSpaceSupplement->fu_ad_tag_url;

                // Validate the supplement
                $errors = validateCustomAdSpaceSupplement($adSpaceSupplement);
                
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
                        if ($adSpaceSupplement->id < 1) {
                                $adSpaceSupplement->id = newCustomAdSpaceSupplement($adSpaceSupplement);
                        }
                        else {
                                updateCustomAdSpaceSupplement($adSpaceSupplement);
                        }
                        
                        deleteAllCategoriesForSupplement($adSpaceSupplement->id);
                        $supplementCategory = new SupplementCategory();
                        $supplementCategory->categoryID = getFirstCategoryIDForItemOfType ($contentTypeCatTable, ($contentType == 'document')?$documentID:$itemID);
                        $supplementCategory->supplementRecordID = $adSpaceSupplement->id;
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
                                        $pageSupplement->supplementRecordID = $adSpaceSupplement->id;
                                        updatePageSupplement($pageSupplement);
                                }
                                else {
                                        $pageSupplement = new PageSupplement();
                                        $pageSupplement->contentType = $contentType;
                                        $pageSupplement->itemID = $itemID;
                                        $pageSupplement->supplementWidgetID = $widget->id;
                                        $pageSupplement->supplementRecordID = $adSpaceSupplement->id;
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
        
        $supplementRecords = getAllCustomAdSpaceSupplements(array());
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
                                <input type="text" name="title" value="<?php print encodeHtml($adSpaceSupplement->title); ?>" class="field" />
                        </td>
                </tr>
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_ad_title1'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Top Left Headline</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_ad_title1" value="<?php print encodeHtml($adSpaceSupplement->fu_ad_title1); ?>" class="field" />
                        </td>
                </tr>
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_ad_title2'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Top Right Link Text</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_ad_title2" value="<?php print encodeHtml($adSpaceSupplement->fu_ad_title2); ?>" class="field"  size="50" />
                        </td>
                </tr>
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_ad_title_link'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Top Right Link URL</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_ad_title_link" value="<?php print encodeHtml($adSpaceSupplement->fu_ad_title_link); ?>" class="field"  size="50" />
                        </td>
                </tr>


<tr>
                        <td class="generic_desc<?php if (isset($errors['fu_ad_src1a'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Image*</p></td>
                        <td class="generic_action">
<input type="hidden" name="imageURL" id="imageURL" value="<?php print encodeHtml($adSpaceSupplement->fu_ad_src1a); ?>" />
<?php
                        if ($adSpaceSupplement->fu_ad_src1a == '') {
?>
                                <img class="img_preview" id="toChange" src="<?php print encodeHtml(NO_IMAGE_IMAGE); ?>" alt="No image selected" title="No image selected" />
<?php
                        }
                        else {
?>
            <img class="img_preview" id="toChange" src="<?php print encodeHtml($imageDirectory . $adSpaceSupplement->fu_ad_src1a) ?>" alt="Selected image" title="Selected image" />
<?php
                        }
?>
       <div>
      <input type="button" class="button" name="imageLibrary" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb', 'imagePreviewID=toChange');" /></div>
                        </td>

                </tr>

                                <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_ad_head'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Headline</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_ad_head" value="<?php print encodeHtml($adSpaceSupplement->fu_ad_head); ?>" class="field"  size="50" maxlength="35" />
                        </td>
                </tr>

                                <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_ad_text'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Text</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_ad_text" value="<?php print encodeHtml($adSpaceSupplement->fu_ad_text); ?>" class="field"  size="50" maxlength="60" />
                        </td>
                </tr>

                       <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_ad_tag'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Tag Line</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_ad_tag" value="<?php print encodeHtml($adSpaceSupplement->fu_ad_tag); ?>" class="field"  size="50" />
                        </td>
                </tr>
                

                 <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_ad_tag_url'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Tag Line URL</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_ad_tag_url" value="<?php print encodeHtml($adSpaceSupplement->fu_ad_tag_url); ?>" class="field"  size="50" />
                        </td>
                </tr>
 
                <!--Submit-->
                 <tr>
                        <td colspan="2" class="generic_finish"><em><?php print $stepCounter++; ?>.</em>
                                <span><input type="submit" class="button" name="save" value="Save Supplement"/></span> 
                        </td>
                </tr>

