<script>
function getimg(){
	var theimg = document.getElementById("toChange").src;
	var imgsrcsp = theimg.split("/");
	theimg = (imgsrcsp[imgsrcsp.length-1]);
	
	var theimg2 = document.getElementById("toChange2").src;
	var imgsrcsp2 = theimg2.split("/");
	theimg2 = (imgsrcsp2[imgsrcsp2.length-1]);
	

//if (document.getElementById("imageURL").value == ''){	
document.getElementById("imageURL").value =  (theimg);
//}else{
//	document.getElementById("imageURL").value = document.getElementById("imageURL").value;
//}

if (document.getElementById("imageFilename2").value == ''){	
document.getElementById("imageFilename2").value =  (theimg2);
}else{
document.getElementById("imageFilename2").value = document.getElementById("imageFilename2").value
}


}

</script>
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
                $infographicSupplement = getCustomInfographic($supplementRecordID);
        }
        else {
                $infographicSupplement = new CustomInfographicSupplement();
        }
        
        if (isset($_POST['save'])) {
                $infographicSupplement->title = isset($_POST['title']) ? $_POST['title'] : $infographicSupplement->title;
                $infographicSupplement->fu_fbtn_2_text = isset($_POST['fu_fbtn_2_text']) ? $_POST['fu_fbtn_2_text'] : $infographicSupplement->fu_fbtn_2_text;
                $infographicSupplement->fu_fbtn_2 = isset($_POST['fu_fbtn_2']) ? $_POST['fu_fbtn_2'] : $infographicSupplement->fu_fbtn_2;
                
                 $infographicSupplement->imageFilename = isset($_POST['imageURL']) ? $_POST['imageURL'] : $infographicSupplement->imageFilename;
				 
				$infographicSupplement->imageFilename2 = isset($_POST['imageFilename2']) ? $_POST['imageFilename2'] : $infographicSupplement->imageFilename2;
				
                $infographicSupplement->fu_img_text1 = isset($_POST['fu_img_text1']) ? $_POST['fu_img_text1'] : $infographicSupplement->fu_img_text1;
               

                // Validate the supplement
                $errors = validateCustomInfographicSupplement($infographicSupplement);
                
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
                        if ($infographicSupplement->id < 1) {
                                $infographicSupplement->id = newCustomInfographicSupplement($infographicSupplement);
                        }
                        else {
                                updateCustomInfographicSupplement($infographicSupplement);
                        }
                        
                        deleteAllCategoriesForSupplement($infographicSupplement->id);
                        $supplementCategory = new SupplementCategory();
                        $supplementCategory->categoryID = getFirstCategoryIDForItemOfType ($contentTypeCatTable, ($contentType == 'document')?$documentID:$itemID);
                        $supplementCategory->supplementRecordID = $infographicSupplement->id;
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
                                        $pageSupplement->supplementRecordID = $infographicSupplement->id;
                                        updatePageSupplement($pageSupplement);
                                }
                                else {
                                        $pageSupplement = new PageSupplement();
                                        $pageSupplement->contentType = $contentType;
                                        $pageSupplement->itemID = $itemID;
                                        $pageSupplement->supplementWidgetID = $widget->id;
                                        $pageSupplement->supplementRecordID = $infographicSupplement->id;
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
        
        $supplementRecords = getAllCustomInfographicSupplements(array());
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
                                <input type="text" name="title" value="<?php print encodeHtml($infographicSupplement->title); ?>" class="field" />
                        </td>
                </tr>
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_fbtn_2_text'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Top Right Head</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_fbtn_2_text" value="<?php print encodeHtml($infographicSupplement->fu_fbtn_2_text); ?>" class="field" />
                        </td>
                </tr>
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_fbtn_2'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Top Right Link Text</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_fbtn_2" value="<?php print encodeHtml($infographicSupplement->fu_fbtn_2); ?>" class="field"  size="50" />
                        </td>
                </tr>
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_ad_title_link'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Top Right Link URL</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_ad_title_link" value="<?php print encodeHtml($infographicSupplement->fu_ad_title_link); ?>" class="field"  size="50" />
                        </td>
                </tr>


  <tr>
         <td class="generic_desc<?php if (isset($errors['imageFilename'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Foreground Image*</p></td>
                        <td class="generic_action">
         <input type="hidden" name="imageURL" id="imageURL" value="<?php print encodeHtml($infographicSupplement->imageFilename); ?>" />
<?php
           if ($infographicSupplement->imageFilename == '') {
?>
            <img class="img_preview" id="toChange" src="<?php print encodeHtml(NO_IMAGE_IMAGE); ?>" alt="No image selected" title="No image selected" />
<?php
                        }
              else {
?>
           <img class="img_preview" id="toChange" src="<?php print encodeHtml($imageDirectory . $infographicSupplement->imageFilename) ?>" alt="Selected image" title="Selected image" />
<?php
                        }
?>
          <div><input type="button" class="button" name="imageLibrary" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb', 'imagePreviewID=toChange');" /></div>
                        </td>
                </tr>
                
      <tr>
           <td class="generic_desc<?php if (isset($errors['imageFilename2'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Background Image*</p></td>
                        <td class="generic_action">
<input type="hidden" name="imageFilename2" id="imageFilename2" value="" />
<?php
                        if ($infographicSupplement->imageFilename2 == '') {
?>
                                <img class="img_preview" id="toChange2" src="<?php print encodeHtml(NO_IMAGE_IMAGE); ?>" alt="No image selected" title="No image selected" />
<?php
                        }
                        else {
?>
            <img class="img_preview" id="toChange2" src="<?php print encodeHtml($imageDirectory . $infographicSupplement->imageFilename2) ?>" alt="Selected image" title="Selected image" />
<?php
                        }
?>
       <div>
      <input type="button" class="button" name="imageLibrary2" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb', 'imagePreviewID=toChange2');" /></div>
                        </td>
                </tr> 

                                <tr>
                        <td class="generic_desc<?php if (isset($errors['fu_img_text1'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Caption</p></td>
                        <td class="generic_action">
                                <input type="text" name="fu_img_text1" value="<?php print encodeHtml($infographicSupplement->fu_img_text1); ?>" class="field"  size="70" maxlength="65" />
                        </td>
                </tr>

 
                <!--Submit-->
                 <tr>
                        <td colspan="2" class="generic_finish"><em><?php print $stepCounter++; ?>.</em>
                                <span><input type="submit" class="button" name="save" value="Save Supplement" onclick="getimg();" /></span> 
                        </td>
                </tr>

