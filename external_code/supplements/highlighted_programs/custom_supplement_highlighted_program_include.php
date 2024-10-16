<script>
function getimg(){
	var theimg = document.getElementById("toChange").src;
	var imgsrcsp = theimg.split("/");
	theimg = (imgsrcsp[imgsrcsp.length-1]);
	
	var theimg2 = document.getElementById("toChange2").src;
	var imgsrcsp2 = theimg2.split("/");
	theimg2 = (imgsrcsp2[imgsrcsp2.length-1]);
	
	var theimg3 = document.getElementById("toChange3").src;
	var imgsrcsp3 = theimg3.split("/");
	theimg3 = (imgsrcsp3[imgsrcsp3.length-1]);

//if (document.getElementById("imageURL").value == ''){	
document.getElementById("imageURL").value =  (theimg);
//}else{
//	document.getElementById("imageURL").value = document.getElementById("imageURL").value;
//}

if (document.getElementById("imageFilename_2").value == ''){	
document.getElementById("imageFilename_2").value =  (theimg2);
}else{
document.getElementById("imageFilename_2").value = document.getElementById("imageFilename_2").value
}

if (document.getElementById("imageFilename_3").value == ''){	
document.getElementById("imageFilename_3").value =  (theimg3);
}else{
document.getElementById("imageFilename_3").value = document.getElementById("imageFilename_3").value
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
                $highlightedProgramSupplement = getCustomHPSupplement($supplementRecordID);
        }
        else {
                $highlightedProgramSupplement = new CustomHPSupplement();
        }
        
        if (isset($_POST['save'])) {
                $highlightedProgramSupplement->title = isset($_POST['title']) ? $_POST['title'] : $highlightedProgramSupplement->title;
                $highlightedProgramSupplement->imageFilename_1 = isset($_POST['imageURL']) ? $_POST['imageURL'] : $highlightedProgramSupplement->imageFilename_1;
				$highlightedProgramSupplement->fp_name_1 = isset($_POST['fp_name_1']) ? $_POST['fp_name_1'] : $highlightedProgramSupplement->fp_name_1;
				$highlightedProgramSupplement->url_1 = isset($_POST['url_1']) ? $_POST['url_1'] : checkURLForHTTP($highlightedProgramSupplement->url_1);
                $highlightedProgramSupplement->description_1 = isset($_POST['description_1']) ? $_POST['description_1'] : $highlightedProgramSupplement->description_1;
                
				$highlightedProgramSupplement->imageFilename_2 = isset($_POST['imageFilename_2']) ? $_POST['imageFilename_2'] : $highlightedProgramSupplement->imageFilename_2;
				$highlightedProgramSupplement->fp_name_2 = isset($_POST['fp_name_2']) ? $_POST['fp_name_2'] : $highlightedProgramSupplement->fp_name_2;
				$highlightedProgramSupplement->url_2 = isset($_POST['url_2']) ? $_POST['url_2'] : checkURLForHTTP($highlightedProgramSupplement->url_2);
                $highlightedProgramSupplement->description_2 = isset($_POST['description_2']) ? $_POST['description_2'] : $highlightedProgramSupplement->description_2;
				
				$highlightedProgramSupplement->imageFilename_3 = isset($_POST['imageFilename_3']) ? $_POST['imageFilename_3'] : $highlightedProgramSupplement->imageFilename_3;
				$highlightedProgramSupplement->fp_name_3 = isset($_POST['fp_name_3']) ? $_POST['fp_name_3'] : $highlightedProgramSupplement->fp_name_3;
				$highlightedProgramSupplement->url_3 = isset($_POST['url_3']) ? $_POST['url_3'] : checkURLForHTTP($highlightedProgramSupplement->url_3);
                $highlightedProgramSupplement->description_3 = isset($_POST['description_3']) ? $_POST['description_3'] : $highlightedProgramSupplement->description_3;


                // Validate the supplement
                $errors = validateCustomHPSupplement($highlightedProgramSupplement);
                
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
                        if ($highlightedProgramSupplement->id < 1) {
                                $highlightedProgramSupplement->id = newCustomHPSupplement($highlightedProgramSupplement);
                        }
                        else {
                                updateCustomHPSupplement($highlightedProgramSupplement);
                        }
                        
                        deleteAllCategoriesForSupplement($highlightedProgramSupplement->id);

                        $supplementCategory = new SupplementCategory();
                        $supplementCategory->categoryID = getFirstCategoryIDForItemOfType ($contentTypeCatTable, ($contentType == 'document')?$documentID:$itemID);
                        $supplementCategory->supplementRecordID = $highlightedProgramSupplement->id;
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
                                        $pageSupplement->supplementRecordID = $highlightedProgramSupplement->id;
                                        updatePageSupplement($pageSupplement);
                                }
                                else {
                                        $pageSupplement = new PageSupplement();
                                        $pageSupplement->contentType = $contentType;
                                        $pageSupplement->itemID = $itemID;
                                        $pageSupplement->supplementWidgetID = $widget->id;
                                        $pageSupplement->supplementRecordID = $highlightedProgramSupplement->id;
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
        
        $supplementRecords = getAllCustomHPSupplements(array());
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
                        <td class="generic_desc<?php if (isset($errors['title'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Highlighted Programs Title*</p></td>
                        <td class="generic_action">
                                <input type="text" name="title" value="<?php print encodeHtml($highlightedProgramSupplement->title); ?>" class="field" />
                        </td>
                </tr>
                 <tr><td colspan="2">Highlighted Program 1</td></tr>   
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['fp_name_1'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Title 1*</p></td>
                        <td class="generic_action">
                                <input type="text" name="fp_name_1" value="<?php print encodeHtml($highlightedProgramSupplement->fp_name_1); ?>" class="field" />
                        </td>
                </tr>
                
                <tr>
         <td class="generic_desc<?php if (isset($errors['imageFilename_1'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Image*</p></td>
                        <td class="generic_action">
         <input type="hidden" name="imageURL" id="imageURL" value="<?php print encodeHtml($highlightedProgramSupplement->imageFilename_1); ?>" />
<?php
           if ($highlightedProgramSupplement->imageFilename_1 == '') {
?>
            <img class="img_preview" id="toChange" src="<?php print encodeHtml(NO_IMAGE_IMAGE); ?>" alt="No image selected" title="No image selected" />
<?php
                        }
              else {
?>
           <img class="img_preview" id="toChange" src="<?php print encodeHtml($imageDirectory . $highlightedProgramSupplement->imageFilename_1) ?>" alt="Selected image" title="Selected image" />
<?php
                        }
?>
          <div><input type="button" class="button" name="imageLibrary" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb', 'imagePreviewID=toChange');" /></div>
                        </td>
                </tr>

                <tr>
                        <td class="generic_desc<?php if (isset($errors['url_1'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Link destination*</p></td>
                        <td class="generic_action">
                                <input type="text" name="url_1" value="<?php print encodeHtml($highlightedProgramSupplement->url_1); ?>" class="field"  size="50" />
                        </td>
                </tr>
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['description_1'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Description*</p></td>
                        <td class="generic_action">
                                <input type="text" name="description_1" value="<?php print encodeHtml($highlightedProgramSupplement->description_1); ?>" class="field"  size="50" />
                        </td>
                </tr>
                
               
                

 <!--2--> 
 <tr><td colspan="2">Highlighted Program 2</td></tr>
 
 <tr>
                        <td class="generic_desc<?php if (isset($errors['fp_name_2'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Title 2*</p></td>
                        <td class="generic_action">
                                <input type="text" name="fp_name_2" value="<?php print encodeHtml($highlightedProgramSupplement->fp_name_2); ?>" class="field" />
                        </td>
                </tr>
                 <tr>
           <td class="generic_desc<?php if (isset($errors['imageFilename_2'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Image 2*</p></td>
                        <td class="generic_action">
<input type="hidden" name="imageFilename_2" id="imageFilename_2" value="" />
<?php
                        if ($highlightedProgramSupplement->imageFilename_2 == '') {
?>
                                <img class="img_preview" id="toChange2" src="<?php print encodeHtml(NO_IMAGE_IMAGE); ?>" alt="No image selected" title="No image selected" />
<?php
                        }
                        else {
?>
            <img class="img_preview" id="toChange2" src="<?php print encodeHtml($imageDirectory . $highlightedProgramSupplement->imageFilename_2) ?>" alt="Selected image" title="Selected image" />
<?php
                        }
?>
       <div>
      <input type="button" class="button" name="imageLibrary2" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb', 'imagePreviewID=toChange2');" /></div>
                        </td>
                </tr>
                 <tr>
                        <td class="generic_desc<?php if (isset($errors['url_2'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Link destination 2*</p></td>
                        <td class="generic_action">
                                <input type="text" name="url_2" value="<?php print encodeHtml($highlightedProgramSupplement->url_2); ?>" class="field" size="50" />
                        </td>
                </tr>
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['description_2'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Description 2*</p></td>
                        <td class="generic_action">
                                <input type="text" name="description_2" value="<?php print encodeHtml($highlightedProgramSupplement->description_2); ?>" class="field"  size="50"/>
                        </td>
                </tr>
   
                
                
 <!--3-->   
 <tr><td colspan="2">Highlighted Program 3</td></tr>             
                <tr>
            <td class="generic_desc<?php if (isset($errors['fp_name_3'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Title 3*</p></td>
                        <td class="generic_action">
      <input type="text" name="fp_name_3" value="<?php print encodeHtml($highlightedProgramSupplement->fp_name_3); ?>" class="field" />
                        </td>
                </tr>
                
                <tr>
           <td class="generic_desc<?php if (isset($errors['imageFilename_3'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Image 3*</p></td>
                        <td class="generic_action">
<input type="hidden" name="imageFilename_3" id="imageFilename_3" value="" />
<?php
                        if ($highlightedProgramSupplement->imageFilename_3 == '') {
?>
                                <img class="img_preview" id="toChange3" src="<?php print encodeHtml(NO_IMAGE_IMAGE); ?>" alt="No image selected" title="No image selected" />
<?php
                        }
                        else {
?>
            <img class="img_preview" id="toChange3" src="<?php print encodeHtml($imageDirectory . $highlightedProgramSupplement->imageFilename_3) ?>" alt="Selected image" title="Selected image" />
<?php
                        }
?>
       <div>
      <input type="button" class="button" name="imageLibrary3" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb', 'imagePreviewID=toChange3');" /></div>
                        </td>
                </tr>
                 <tr>
                        <td class="generic_desc<?php if (isset($errors['url_3'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Link Destination 3*</p></td>
                        <td class="generic_action">
                                <input type="text" name="url_3" value="<?php print encodeHtml($highlightedProgramSupplement->url_3); ?>" class="field"  size="50" />
                        </td>
                </tr>
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['description_3'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Description 3*</p></td>
                        <td class="generic_action">
                                <input type="text" name="description_3" value="<?php print encodeHtml($highlightedProgramSupplement->description_3); ?>" class="field"  size="50" />
                        </td>
                </tr>
                
                
                <!--Submit-->
                 <tr>
                        <td colspan="2" class="generic_finish"><em><?php print $stepCounter++; ?>.</em>
                                <span><input type="submit" class="button" name="save" value="Save Supplement" onclick="getimg();" /></span> 
                        </td>
                </tr>

