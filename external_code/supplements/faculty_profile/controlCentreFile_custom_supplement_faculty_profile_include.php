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

if (document.getElementById("imageFilename2").value == ''){	
document.getElementById("imageFilename2").value =  (theimg2);
}else{
document.getElementById("imageFilename2").value = document.getElementById("imageFilename2").value
}

if (document.getElementById("imageFilename3").value == ''){	
document.getElementById("imageFilename3").value =  (theimg3);
}else{
document.getElementById("imageFilename3").value = document.getElementById("imageFilename3").value
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
                $facultyProfileSupplement = getCustomFacultyProfileSupplement($supplementRecordID);
        }
        else {
                $facultyProfileSupplement = new CustomFacultyProfileSupplement();
        }
        
        if (isset($_POST['save'])) {
                $facultyProfileSupplement->title = isset($_POST['title']) ? $_POST['title'] : $facultyProfileSupplement->title;
                $facultyProfileSupplement->imageFilename = isset($_POST['imageURL']) ? $_POST['imageURL'] : $facultyProfileSupplement->imageFilename;
				$facultyProfileSupplement->fp_name = isset($_POST['fp_name']) ? $_POST['fp_name'] : $facultyProfileSupplement->fp_name;
				$facultyProfileSupplement->url = isset($_POST['url']) ? $_POST['url'] : checkURLForHTTP($facultyProfileSupplement->url);
                $facultyProfileSupplement->description = isset($_POST['description']) ? $_POST['description'] : $facultyProfileSupplement->description;
                
				$facultyProfileSupplement->imageFilename2 = isset($_POST['imageFilename2']) ? $_POST['imageFilename2'] : $facultyProfileSupplement->imageFilename2;
				$facultyProfileSupplement->fp_name2 = isset($_POST['fp_name2']) ? $_POST['fp_name2'] : $facultyProfileSupplement->fp_name2;
				$facultyProfileSupplement->url2 = isset($_POST['url2']) ? $_POST['url2'] : checkURLForHTTP($facultyProfileSupplement->url2);
                $facultyProfileSupplement->description2 = isset($_POST['description2']) ? $_POST['description2'] : $facultyProfileSupplement->description2;
				
				$facultyProfileSupplement->imageFilename3 = isset($_POST['imageFilename3']) ? $_POST['imageFilename3'] : $facultyProfileSupplement->imageFilename3;
				$facultyProfileSupplement->fp_name3 = isset($_POST['fp_name3']) ? $_POST['fp_name3'] : $facultyProfileSupplement->fp_name;
				$facultyProfileSupplement->url3 = isset($_POST['url3']) ? $_POST['url3'] : checkURLForHTTP($facultyProfileSupplement->url3);
                $facultyProfileSupplement->description3 = isset($_POST['description3']) ? $_POST['description3'] : $facultyProfileSupplement->description3;


                // Validate the supplement
                $errors = validateCustomFacultyProfileSupplement($facultyProfileSupplement);
                
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
                        if ($facultyProfileSupplement->id < 1) {
                                $facultyProfileSupplement->id = newCustomFacultyProfileSupplement($facultyProfileSupplement);
                        }
                        else {
                                updateCustomFacultyProfileSupplement($facultyProfileSupplement);
                        }
                        
                        deleteAllCategoriesForSupplement($facultyProfileSupplement->id);

                        $supplementCategory = new SupplementCategory();
                        $supplementCategory->categoryID = getFirstCategoryIDForItemOfType ($contentTypeCatTable, ($contentType == 'document')?$documentID:$itemID);
                        $supplementCategory->supplementRecordID = $facultyProfileSupplement->id;
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
                                        $pageSupplement->supplementRecordID = $facultyProfileSupplement->id;
                                        updatePageSupplement($pageSupplement);
                                }
                                else {
                                        $pageSupplement = new PageSupplement();
                                        $pageSupplement->contentType = $contentType;
                                        $pageSupplement->itemID = $itemID;
                                        $pageSupplement->supplementWidgetID = $widget->id;
                                        $pageSupplement->supplementRecordID = $facultyProfileSupplement->id;
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
        
        $supplementRecords = getAllCustomFacultyProfileSupplements(array());
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
                                <input type="text" name="title" value="<?php print encodeHtml($facultyProfileSupplement->title); ?>" class="field" />
                        </td>
                </tr>
                 <tr><td colspan="2">First Profile</td></tr>   
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['fp_name'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Name of First Person Profiled*</p></td>
                        <td class="generic_action">
                                <input type="text" name="fp_name" value="<?php print encodeHtml($facultyProfileSupplement->fp_name); ?>" class="field" />
                        </td>
                </tr>
                
                <tr>
         <td class="generic_desc<?php if (isset($errors['imageFilename'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Image*</p></td>
                        <td class="generic_action">
         <input type="hidden" name="imageURL" id="imageURL" value="<?php print encodeHtml($facultyProfileSupplement->imageFilename); ?>" />
<?php
           if ($facultyProfileSupplement->imageFilename == '') {
?>
            <img class="img_preview" id="toChange" src="<?php print encodeHtml(NO_IMAGE_IMAGE); ?>" alt="No image selected" title="No image selected" />
<?php
                        }
              else {
?>
           <img class="img_preview" id="toChange" src="<?php print encodeHtml($imageDirectory . $facultyProfileSupplement->imageFilename) ?>" alt="Selected image" title="Selected image" />
<?php
                        }
?>
          <div><input type="button" class="button" name="imageLibrary" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb', 'imagePreviewID=toChange');" /></div>
                        </td>
                </tr>

                <tr>
                        <td class="generic_desc<?php if (isset($errors['url'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Link destination*</p></td>
                        <td class="generic_action">
                                <input type="text" name="url" value="<?php print encodeHtml($facultyProfileSupplement->url); ?>" class="field"  size="50" />
                        </td>
                </tr>
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['description'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Description*</p></td>
                        <td class="generic_action">
                                <input type="text" name="description" value="<?php print encodeHtml($facultyProfileSupplement->description); ?>" class="field"  size="50" />
                        </td>
                </tr>
                
               
                

 <!--2--> 
 <tr><td colspan="2">Second Profile</td></tr>
 
 <tr>
                        <td class="generic_desc<?php if (isset($errors['fp_name2'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Name of Second Person Profiled*</p></td>
                        <td class="generic_action">
                                <input type="text" name="fp_name2" value="<?php print encodeHtml($facultyProfileSupplement->fp_name2); ?>" class="field" />
                        </td>
                </tr>
                 <tr>
           <td class="generic_desc<?php if (isset($errors['imageFilename2'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Image2*</p></td>
                        <td class="generic_action">
<input type="hidden" name="imageFilename2" id="imageFilename2" value="" />
<?php
                        if ($facultyProfileSupplement->imageFilename2 == '') {
?>
                                <img class="img_preview" id="toChange2" src="<?php print encodeHtml(NO_IMAGE_IMAGE); ?>" alt="No image selected" title="No image selected" />
<?php
                        }
                        else {
?>
            <img class="img_preview" id="toChange2" src="<?php print encodeHtml($imageDirectory . $facultyProfileSupplement->imageFilename2) ?>" alt="Selected image" title="Selected image" />
<?php
                        }
?>
       <div>
      <input type="button" class="button" name="imageLibrary2" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb', 'imagePreviewID=toChange2');" /></div>
                        </td>
                </tr>
                 <tr>
                        <td class="generic_desc<?php if (isset($errors['url2'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Link destination 2*</p></td>
                        <td class="generic_action">
                                <input type="text" name="url2" value="<?php print encodeHtml($facultyProfileSupplement->url2); ?>" class="field" size="50" />
                        </td>
                </tr>
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['description2'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Description 2*</p></td>
                        <td class="generic_action">
                                <input type="text" name="description2" value="<?php print encodeHtml($facultyProfileSupplement->description2); ?>" class="field"  size="50"/>
                        </td>
                </tr>
   
                
                
 <!--3-->   
 <tr><td colspan="2">Third Profile</td></tr>             
                <tr>
            <td class="generic_desc<?php if (isset($errors['fp_name3'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Name of Third Person Profiled*</p></td>
                        <td class="generic_action">
      <input type="text" name="fp_name3" value="<?php print encodeHtml($facultyProfileSupplement->fp_name3); ?>" class="field" />
                        </td>
                </tr>
                
                <tr>
           <td class="generic_desc<?php if (isset($errors['imageFilename3'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Image3*</p></td>
                        <td class="generic_action">
<input type="hidden" name="imageFilename3" id="imageFilename3" value="" />
<?php
                        if ($facultyProfileSupplement->imageFilename3 == '') {
?>
                                <img class="img_preview" id="toChange3" src="<?php print encodeHtml(NO_IMAGE_IMAGE); ?>" alt="No image selected" title="No image selected" />
<?php
                        }
                        else {
?>
            <img class="img_preview" id="toChange3" src="<?php print encodeHtml($imageDirectory . $facultyProfileSupplement->imageFilename3) ?>" alt="Selected image" title="Selected image" />
<?php
                        }
?>
       <div>
      <input type="button" class="button" name="imageLibrary3" value="Image Library" onclick="return loadLightbox('image_manager/image_manager', 'lb', 'imagePreviewID=toChange3');" /></div>
                        </td>
                </tr>
                 <tr>
                        <td class="generic_desc<?php if (isset($errors['url3'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Link Destination 3*</p></td>
                        <td class="generic_action">
                                <input type="text" name="url3" value="<?php print encodeHtml($facultyProfileSupplement->url3); ?>" class="field"  size="50" />
                        </td>
                </tr>
                
                <tr>
                        <td class="generic_desc<?php if (isset($errors['description3'])) { ?>_error<?php } ?>"><em><?php print $stepCounter++; ?>.</em> <p>Description 3*</p></td>
                        <td class="generic_action">
                                <input type="text" name="description3" value="<?php print encodeHtml($facultyProfileSupplement->description3); ?>" class="field"  size="50" />
                        </td>
                </tr>
                
                
                <!--Submit-->
                 <tr>
                        <td colspan="2" class="generic_finish"><em><?php print $stepCounter++; ?>.</em>
                                <span><input type="submit" class="button" name="save" value="Save Supplement" onclick="getimg();" /></span> 
                        </td>
                </tr>

