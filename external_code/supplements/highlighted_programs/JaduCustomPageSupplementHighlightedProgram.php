<?php
        /** classFile "JaduCustomPageSupplementHighlightedProgram.php"
        * physical location: /var/www/jadu/jadu/custom/
        * Custom Image Link supplement widget.
        *
        * @package custom
        * @copyright All Contents (c) 2007 Jadu Ltd.
        * @author Jadu Ltd.
        */

        /**
        * The database table used to hold image links 
        */
  define("CUSTOM_PAGE_SUPPLEMENT_HIGHLIGHTED_PROGRAM_TABLE", "JaduCustomPageSupplementHighlightedProgram");

        include_once("JaduADODB.php");
        include_once("JaduCache.php");
        include_once('websections/JaduPageSupplements.php');

        /**
        * The CustomHPSupplement class contains a set of fields relating to a CustomHPSupplement.
        *
        * @package custom
        */
        class CustomHPSupplement
        {
                /**
                * The id of the {@link CustomHPSupplement} in the database.
                * @access public
                * @var integer
                */              
                var $id = -1;
        
                /**
                * The title of this {@link CustomHPSupplement}
                * @access public
                * @var string
                */      
                var $title = '';

                                 /**
                * The Name of the person Profiled of this {@link CustomHPSupplement}
                * @access public
                * @var string
                */      
                var $fp_name_1 = '';

                /**
                * The filename of an image that is assigned to this {@link CustomHPSupplement}
                * @access public
                * @var string
                */      
                var $imageFilename_1 = '';
        
              
                /**
                * The URL assigned to the link in this {@link CustomHPSupplement}
                * @access public
                * @var string
                */              
                var $url_1 = '';

                                /**
                * The DESCRIPTION assigned to the link in this {@link CustomHPSupplement}
                * @access public
                * @var string
                */              
                var $description_1 = '';

                                 /**
                * The Name of the person Profiled of this {@link CustomHPSupplement}
                * @access public
                * @var string
                */      
                var $fp_name_2 = '';

                /**
                * The filename of an image that is assigned to this {@link CustomHPSupplement}
                * @access public
                * @var string
                */      
                var $imageFilename_2 = '';
        
              
                /**
                * The URL assigned to the link in this {@link CustomHPSupplement}
                * @access public
                * @var string
                */              
                var $url_2 = '';

                                /**
                * The DESCRIPTION assigned to the link in this {@link CustomHPSupplement}
                * @access public
                * @var string
                */              
                var $description_2 = '';

                                                 /**
                * The Name of the person Profiled of this {@link CustomHPSupplement}
                * @access public
                * @var string
                */      
                var $fp_name_3 = '';

                /**
                * The filename of an image that is assigned to this {@link CustomHPSupplement}
                * @access public
                * @var string
                */      
                var $imageFilename_3 = '';
        
              
                /**
                * The URL assigned to the link in this {@link CustomHPSupplement}
                * @access public
                * @var string
                */              
                var $url_3 = '';

                                /**
                * The DESCRIPTION assigned to the link in this {@link CustomHPSupplement}
                * @access public
                * @var string
                */              
                var $description_3 = '';

        }
        
        /**
         * Build the SQL query for the given criteria.
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @param array[]string $order List of fields to order results by
         * @param boolean $count Whether the query is a COUNT query
         * @return string The generated SQL query
         */
        function buildCustomHPSupplementQuery($criteria = array(), $order = array(),
                $count = false)
        {
                global $db;
                
                $whereClause = '';
                
                if (isset($criteria['id'])) {
                        $whereClause .= (empty($whereClause) ? 'WHERE' : 'AND') . ' id = ' . intval($criteria['id']) . ' ';
                }
                
                if (isset($criteria['title'])) {
                        $whereClause .= (empty($whereClause) ? 'WHERE' : 'AND') . ' title = ' . $db->qstr($criteria['title']) . ' ';
                }
                
                if (isset($criteria['except'])) {
                        if (!is_array($criteria['except'])) {
                                $criteria['except'] = array($criteria['except']);
                        }
                        $whereClause .= (empty($whereClause) ? 'WHERE' : 'AND') . ' id NOT IN (' . implode(',', array_map('intval', $criteria['except'])) . ') ';
                }
                
                $orderClause = '';

                // Build the query string
                if ($count) {
                        $query = 'SELECT COUNT(*) AS numRows ';
                }
                else {
                        $query = 'SELECT id, title, fp_name_1, imageFilename_1, url_1, description_1, fp_name_2, imageFilename_2, url_2, description_2, fp_name_3, imageFilename_3, url_3, description_3 ';
                        
                        // Order by
                        if (is_string($order)) {
                                $order = array($order);
                        }
                        
                        if (count($order) > 0) {
                                $orderClause = 'ORDER BY ' . implode(',', $order) . ' ';
                        }
                        else {
                                $orderClause = 'ORDER BY title ASC';
                        }
                }

                $query .= 'FROM ' . CUSTOM_PAGE_SUPPLEMENT_HIGHLIGHTED_PROGRAM_TABLE . ' ' .
                                  $whereClause . 
                                  $orderClause;
                
                return $query;
        }
        
        /**
         * Fetch a single CustomHPSupplement item using the given query.
         *
         * @param string $query The query to execute.
         * @return CustomHPSupplement A single matching CustomHPSupplement or an empty CustomHPSupplement object.
         */
        function fetchCustomHPSupplementWithQuery($query)
        {
                global $db;
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_HIGHLIGHTED_PROGRAM_TABLE, $query);
                if ($cache->isEmpty()) {
                        $supplement = new CustomHPSupplement();
                        $result = $db->SelectLimit($query, 1);
                        if ($result && !$result->EOF) {
                                $supplement->id = (int) $result->fields['id'];
                                $supplement->title = $result->fields['title'];
                                $supplement->fp_name_1 = $result->fields['fp_name_1'];
                                $supplement->imageFilename_1 = $result->fields['imageFilename_1'];
                                $supplement->url_1 = $result->fields['url_1'];
                                $supplement->description_1 = $result->fields['description_1'];

                                                                $supplement->fp_name_2 = $result->fields['fp_name_2'];
                                $supplement->imageFilename_2 = $result->fields['imageFilename_2'];
                                $supplement->url_2 = $result->fields['url_2'];
                                $supplement->description_2 = $result->fields['description_2'];

                                                                $supplement->fp_name_3 = $result->fields['fp_name_3'];
                                $supplement->imageFilename_3 = $result->fields['imageFilename_3'];
                                $supplement->url_3 = $result->fields['url_3'];
                                $supplement->description_3 = $result->fields['description_3'];
                                
                        }
                        
                        $cache->setData($supplement);
                        return $supplement;
                }
                
                return $cache->data;
        }
        
        /**
         * Fetch all CustomHPSupplement for the given query.
         *
         * @param string $query The SQL query to execute
         * @param integer $limit The number of records to return (null = all)
         * @param integer $offset The position in the result set to begin returning records from
         * @return array[]CustomHPSupplement Array of matching CustomHPSupplement objects
         */
        function fetchAllCustomHPSupplementsWithQuery($query, $limit = null, $offset = null)
        {
                global $db;
                
                $cacheId = $query;
                if ($limit !== null) {
                        $cacheId .= ' LIMIT ' . (int) $limit . ',' . (int) $offset;
                }
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_HIGHLIGHTED_PROGRAM_TABLE, $cacheId);
                if ($cache->isEmpty()) {
                        if ($limit === null) {
                                $result = $db->Execute($query);
                        }
                        else {
                                $result = $db->SelectLimit($query, $limit, $offset);
                        }
                        
                        $supplements = array();


                        if ($result) {
                                while (!$result->EOF) {
                                        $supplement = new CustomHPSupplement();
                                        $supplement->id = (int) $result->fields['id'];
                                        $supplement->title = $result->fields['title'];
                                        $supplement->fp_name_1 = $result->fields['fp_name_1'];
                                        $supplement->imageFilename_1 = $result->fields['imageFilename_1'];
                                        $supplement->url_1 = $result->fields['url_1'];
                                        $supplement->description_1 = $result->fields['description_1'];

                                                                                $supplement->fp_name_2 = $result->fields['fp_name_2'];
                                        $supplement->imageFilename_2 = $result->fields['imageFilename_2'];
                                        $supplement->url_2 = $result->fields['url_2'];
                                        $supplement->description_2 = $result->fields['description_2'];

                                                                                $supplement->fp_name_3 = $result->fields['fp_name_3'];
                                        $supplement->imageFilename_3 = $result->fields['imageFilename_3'];
                                        $supplement->url_3 = $result->fields['url_3'];
                                        $supplement->description_3 = $result->fields['description_3'];
                                        
                                        $supplements[] = $supplement;
                                        
                                        $result->MoveNext();
                                }
                        }
                        
                        $cache->setData($supplements);
                        return $supplements;
                }
                
                return $cache->data;
        }

        /**
         * Creates a new CustomHPSupplement supplement record in the database.
         *
         * @param CustomHPSupplement $CustomHPSupplement An instance of CustomHPSupplement to add to the database.
         * @return integer The database id of the newly created record.
         */
        function newCustomHPSupplement($CustomHPSupplement)
        {
                global $db;

                 $query = 'INSERT INTO ' . CUSTOM_PAGE_SUPPLEMENT_HIGHLIGHTED_PROGRAM_TABLE . ' ' . 
                                 '(title, fp_name_1, imageFilename_1, url_1, description_1, fp_name_2, imageFilename_2, url_2, description_2, fp_name_3, imageFilename_3, url_3, description_3) VALUES (' . 
                                 $db->qstr($CustomHPSupplement->title) . ',' .
                                 $db->qstr($CustomHPSupplement->fp_name_1) . ',' . 
                                 $db->qstr($CustomHPSupplement->imageFilename_1) . ',' .
                                  $db->qstr($CustomHPSupplement->url_1) . ',' . 
                                 $db->qstr($CustomHPSupplement->description_1) . ',' .
								 
								 $db->qstr($CustomHPSupplement->fp_name_2) . ',' . 
                                 $db->qstr($CustomHPSupplement->imageFilename_2) . ',' .
                                  $db->qstr($CustomHPSupplement->url_2) . ',' . 
                                 $db->qstr($CustomHPSupplement->description_2) . ',' .
								 
								 $db->qstr($CustomHPSupplement->fp_name_3) . ',' . 
                                 $db->qstr($CustomHPSupplement->imageFilename_3) . ',' .
                                  $db->qstr($CustomHPSupplement->url_3) . ',' . 
                                 $db->qstr($CustomHPSupplement->description_3) . ')';
                $db->Execute($query);
                $id = $db->Insert_ID();
                
                if ($id > 0) {
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_HIGHLIGHTED_PROGRAM_TABLE);
                }
                
                return (int) $id;
        }

        /**
         * Update CustomHPSupplement supplement database record.
         *
         * @param CustomHPSupplement $CustomHPSupplement An instance of CustomHPSupplement with the details to update.
         */
        function updateCustomHPSupplement($CustomHPSupplement)
        {
                global $db;

                $query = 'UPDATE ' . CUSTOM_PAGE_SUPPLEMENT_HIGHLIGHTED_PROGRAM_TABLE . ' SET ' .
                                 
                                 'title = ' . $db->qstr($CustomHPSupplement->title) . ',' .
                                                                 'fp_name_1 = ' . $db->qstr($CustomHPSupplement->fp_name_1) . ',' .
                                                                 'imageFilename_1 = ' . $db->qstr($CustomHPSupplement->imageFilename_1) . ',' .
                                                                 'description_1 = ' . $db->qstr($CustomHPSupplement->description_1) . ',' .
                                                                 'url_1 = ' . $db->qstr($CustomHPSupplement->url_1) . ',' .
                                                                                                                                 
                                                                                                                                 'fp_name_2 = ' . $db->qstr($CustomHPSupplement->fp_name_2) . ',' .
                                                                 'imageFilename_2 = ' . $db->qstr($CustomHPSupplement->imageFilename_2) . ',' .
                                                                 'description_2 = ' . $db->qstr($CustomHPSupplement->description_2) . ',' .
                                                                 'url_2 = ' . $db->qstr($CustomHPSupplement->url_2) . ',' .
                                                                                                                                 
                                                                 'fp_name_3 = ' . $db->qstr($CustomHPSupplement->fp_name_3) . ',' .
                                                                 'imageFilename_3 = ' . $db->qstr($CustomHPSupplement->imageFilename_3) . ',' .
                                                                 'description_3 = ' . $db->qstr($CustomHPSupplement->description_3) . ',' .
                                                                 'url_3 = ' . $db->qstr($CustomHPSupplement->url_3) . ' ' .
                                 'WHERE id = ' . intval($CustomHPSupplement->id);

                $db->Execute($query);
                $affectedRows = $db->Affected_Rows();
                
                if ($affectedRows > 0) {
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_HIGHLIGHTED_PROGRAM_TABLE);
                }

                return (bool) $affectedRows;
        }

        /**
         * Delete CustomHPSupplement in the database.
         *
         * @param integer $id The id of the CustomHPSupplement to be deleted.
         */
        function deleteCustomHPSupplement($id)
        {
                global $db;

                $query = 'DELETE FROM ' . CUSTOM_PAGE_SUPPLEMENT_HIGHLIGHTED_PROGRAM_TABLE . ' ' .
                                 'WHERE id = ' . intval($id);

                $db->Execute($query);
                $affectedRows = $db->Affected_Rows();
                
                if ($affectedRows > 0) {
                        // Delete all supplements referencing this record
                        deletePageSupplementForRecord($id);
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_HIGHLIGHTED_PROGRAM_TABLE);
                }

                return (bool) $affectedRows;
        }

        /**
         * A function to return an array of CustomHPSupplement objects.
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @param array[]string $order List of fields to order results by
         * @param integer $limit The number of records to return (null = all)
         * @param integer $offset The position in the result set to begin returning records from
         * @return array[]CustomHPSupplement Returns an array of CustomHPSupplement objects.
         */
        function getAllCustomHPSupplements($criteria = array(), $order = array(), $limit = null, $offset = null)
        {
                $query = buildCustomHPSupplementQuery($criteria, $order);
                return fetchAllCustomHPSupplementsWithQuery($query, $limit, $offset);
        }
        
        /**
         * Returns the number of CustomHPSupplement supplements that match a given criteria
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @return integer The number of CustomHPSupplement matching the given criteria
         */
        function countCustomHPSupplements($criteria = array())
        {
                global $db;
                
                $query = buildCustomHPSupplementQuery($criteria, array(), true);
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_HIGHLIGHTED_PROGRAM_TABLE, $query);
                if ($cache->isEmpty()) {
                        $count = 0;
                        $result = $db->Execute($query);
                        if ($result && !$result->EOF) {
                                $count = (int) $db->GetOne($query);
                        }
                        
                        $cache->setData($count);
                        return $count;
                }
                
                return $cache->data;
        }

        /**
         * Get CustomHPSupplement record from the database.
         *
         * @param integer $id The id of the advertSupplement you want.
         * @return CustomHPSupplement Returns an instance of CustomHPSupplement.
         */
        function getCustomHPSupplement($id)
        {
                $query = buildCustomHPSupplementQuery(array('id' => $id));
                return fetchCustomHPSupplementWithQuery($query);
        }
        
        /**
         * Get a CustomHPSupplement record from the database by title
         *
         * @param string $title The title of the advertSupplement you want.
         * @return CustomHPSupplement Returns an instance of CustomHPSupplement.
         */
        function getCustomHPSupplementByTitle($title)
        {
                $query = buildCustomHPSupplementQuery(array('title' => $title));
                return fetchCustomHPSupplementWithQuery($query);
        }

        /**
        * Check that a CustomImageSupplement record is suitable for adding to the database.
        *
        * @param CustomHPSupplement $customImageSupplement An instance of CustomImageSupplement
        * @return array An associative array with the fields that contain errors
        */
        function validateCustomHPSupplement($customImageSupplement)
        {
                $errors = array();

                if (empty($customImageSupplement->title)) {
                        $errors['title'] = true;
                }
                else {
                        $numSupplements = countCustomHPSupplements(array(
                                'title' => $customImageSupplement->title,
                                'except' => $customImageSupplement->id
                        ));
                        
                        if ($numSupplements > 0) {
                                // Title must be unique
                                $errors['title'] = true;
                        }
                }
                
                 if (empty($customImageSupplement->fp_name_1)) {
                        $errors['fp_name_1'] = true;
                }

                if (empty($customImageSupplement->imageFilename_1)) {
                        $errors['imageFilename_1'] = true;
                }

                                if (empty($customImageSupplement->url_1)) {
                        $errors['url_1'] = true;
                }

                                 if (empty($customImageSupplement->description_1)) {
                        $errors['description_1'] = true;
                  }
				  
				  
				 if (empty($customImageSupplement->fp_name_2)) {
                        $errors['fp_name_2'] = true;
                }

                if (empty($customImageSupplement->imageFilename_2)) {
                        $errors['imageFilename_2'] = true;
                }

                                if (empty($customImageSupplement->url_2)) {
                        $errors['url_2'] = true;
                }

                                 if (empty($customImageSupplement->description_2)) {
                        $errors['description_2'] = true;
                  } 


					 if (empty($customImageSupplement->fp_name_3)) {
                        $errors['fp_name_3'] = true;
                }

                if (empty($customImageSupplement->imageFilename_3)) {
                        $errors['imageFilename_3'] = true;
                }

                                if (empty($customImageSupplement->url_3)) {
                        $errors['url_3'] = true;
                }

                                 if (empty($customImageSupplement->description_3)) {
                        $errors['description_3'] = true;
                  }
                return $errors;
        }
?>


