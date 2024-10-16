<?php
        /** classFile "JaduCustomPageSupplementFacultyProfile.php"
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
  define("CUSTOM_PAGE_SUPPLEMENT_FACULTY_PROFILE_TABLE", "JaduCustomPageSupplementFacultyProfile");

        include_once("JaduADODB.php");
        include_once("JaduCache.php");
        include_once('websections/JaduPageSupplements.php');

        /**
        * The CustomFacultyProfileSupplement class contains a set of fields relating to a CustomFacultyProfileSupplement.
        *
        * @package custom
        */
        class CustomFacultyProfileSupplement
        {
                /**
                * The id of the {@link CustomFacultyProfileSupplement} in the database.
                * @access public
                * @var integer
                */              
                var $id = -1;
        
                /**
                * The title of this {@link CustomFacultyProfileSupplement}
                * @access public
                * @var string
                */      
                var $title = '';

                                 /**
                * The Name of the person Profiled of this {@link CustomFacultyProfileSupplement}
                * @access public
                * @var string
                */      
                var $fp_name = '';

                /**
                * The filename of an image that is assigned to this {@link CustomFacultyProfileSupplement}
                * @access public
                * @var string
                */      
                var $imageFilename = '';
        
              
                /**
                * The URL assigned to the link in this {@link CustomFacultyProfileSupplement}
                * @access public
                * @var string
                */              
                var $url = '';

                                /**
                * The DESCRIPTION assigned to the link in this {@link CustomFacultyProfileSupplement}
                * @access public
                * @var string
                */              
                var $description = '';

                                 /**
                * The Name of the person Profiled of this {@link CustomFacultyProfileSupplement}
                * @access public
                * @var string
                */      
                var $fp_name2 = '';

                /**
                * The filename of an image that is assigned to this {@link CustomFacultyProfileSupplement}
                * @access public
                * @var string
                */      
                var $imageFilename2 = '';
        
              
                /**
                * The URL assigned to the link in this {@link CustomFacultyProfileSupplement}
                * @access public
                * @var string
                */              
                var $url2 = '';

                                /**
                * The DESCRIPTION assigned to the link in this {@link CustomFacultyProfileSupplement}
                * @access public
                * @var string
                */              
                var $description2 = '';

                                                 /**
                * The Name of the person Profiled of this {@link CustomFacultyProfileSupplement}
                * @access public
                * @var string
                */      
                var $fp_name3 = '';

                /**
                * The filename of an image that is assigned to this {@link CustomFacultyProfileSupplement}
                * @access public
                * @var string
                */      
                var $imageFilename3 = '';
        
              
                /**
                * The URL assigned to the link in this {@link CustomFacultyProfileSupplement}
                * @access public
                * @var string
                */              
                var $url3 = '';

                                /**
                * The DESCRIPTION assigned to the link in this {@link CustomFacultyProfileSupplement}
                * @access public
                * @var string
                */              
                var $description3 = '';

        }
        
        /**
         * Build the SQL query for the given criteria.
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @param array[]string $order List of fields to order results by
         * @param boolean $count Whether the query is a COUNT query
         * @return string The generated SQL query
         */
        function buildCustomFacultyProfileSupplementQuery($criteria = array(), $order = array(),
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
                        $query = 'SELECT id, title, fp_name, imageFilename, url, description, fp_name2, imageFilename2, url2, description2, fp_name3, imageFilename3, url3, description3 ';
                        
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

                $query .= 'FROM ' . CUSTOM_PAGE_SUPPLEMENT_FACULTY_PROFILE_TABLE . ' ' .
                                  $whereClause . 
                                  $orderClause;
                
                return $query;
        }
        
        /**
         * Fetch a single CustomFacultyProfileSupplement item using the given query.
         *
         * @param string $query The query to execute.
         * @return CustomFacultyProfileSupplement A single matching CustomFacultyProfileSupplement or an empty CustomFacultyProfileSupplement object.
         */
        function fetchCustomFacultyProfileSupplementWithQuery($query)
        {
                global $db;
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_FACULTY_PROFILE_TABLE, $query);
                if ($cache->isEmpty()) {
                        $supplement = new CustomFacultyProfileSupplement();
                        $result = $db->SelectLimit($query, 1);
                        if ($result && !$result->EOF) {
                                $supplement->id = (int) $result->fields['id'];
                                $supplement->title = $result->fields['title'];
                                $supplement->fp_name = $result->fields['fp_name'];
                                $supplement->imageFilename = $result->fields['imageFilename'];
                                $supplement->url = $result->fields['url'];
                                $supplement->description = $result->fields['description'];

                                                                $supplement->fp_name2 = $result->fields['fp_name2'];
                                $supplement->imageFilename2 = $result->fields['imageFilename2'];
                                $supplement->url2 = $result->fields['url2'];
                                $supplement->description2 = $result->fields['description2'];

                                                                $supplement->fp_name3 = $result->fields['fp_name3'];
                                $supplement->imageFilename3 = $result->fields['imageFilename3'];
                                $supplement->url3 = $result->fields['url3'];
                                $supplement->description3 = $result->fields['description3'];
                                
                        }
                        
                        $cache->setData($supplement);
                        return $supplement;
                }
                
                return $cache->data;
        }
        
        /**
         * Fetch all CustomFacultyProfileSupplement for the given query.
         *
         * @param string $query The SQL query to execute
         * @param integer $limit The number of records to return (null = all)
         * @param integer $offset The position in the result set to begin returning records from
         * @return array[]CustomFacultyProfileSupplement Array of matching CustomFacultyProfileSupplement objects
         */
        function fetchAllCustomFacultyProfileSupplementsWithQuery($query, $limit = null, $offset = null)
        {
                global $db;
                
                $cacheId = $query;
                if ($limit !== null) {
                        $cacheId .= ' LIMIT ' . (int) $limit . ',' . (int) $offset;
                }
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_FACULTY_PROFILE_TABLE, $cacheId);
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
                                        $supplement = new CustomFacultyProfileSupplement();
                                        $supplement->id = (int) $result->fields['id'];
                                        $supplement->title = $result->fields['title'];
                                        $supplement->fp_name = $result->fields['fp_name'];
                                        $supplement->imageFilename = $result->fields['imageFilename'];
                                        $supplement->url = $result->fields['url'];
                                        $supplement->description = $result->fields['description'];

                                                                                $supplement->fp_name2 = $result->fields['fp_name2'];
                                        $supplement->imageFilename2 = $result->fields['imageFilename2'];
                                        $supplement->url2 = $result->fields['url2'];
                                        $supplement->description2 = $result->fields['description2'];

                                                                                $supplement->fp_name3 = $result->fields['fp_name3'];
                                        $supplement->imageFilename3 = $result->fields['imageFilename3'];
                                        $supplement->url3 = $result->fields['url3'];
                                        $supplement->description3 = $result->fields['description3'];
                                        
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
         * Creates a new CustomFacultyProfileSupplement supplement record in the database.
         *
         * @param CustomFacultyProfileSupplement $CustomFacultyProfileSupplement An instance of CustomFacultyProfileSupplement to add to the database.
         * @return integer The database id of the newly created record.
         */
        function newCustomFacultyProfileSupplement($CustomFacultyProfileSupplement)
        {
                global $db;

                 $query = 'INSERT INTO ' . CUSTOM_PAGE_SUPPLEMENT_FACULTY_PROFILE_TABLE . ' ' . 
                                 '(title, fp_name, imageFilename, url, description, fp_name2, imageFilename2, url2, description2, fp_name3, imageFilename3, url3, description3) VALUES (' . 
                                 $db->qstr($CustomFacultyProfileSupplement->title) . ',' .
                                 $db->qstr($CustomFacultyProfileSupplement->fp_name) . ',' . 
                                 $db->qstr($CustomFacultyProfileSupplement->imageFilename) . ',' .
                                  $db->qstr($CustomFacultyProfileSupplement->url) . ',' . 
                                 $db->qstr($CustomFacultyProfileSupplement->description) . ',' .
								 
								 $db->qstr($CustomFacultyProfileSupplement->fp_name2) . ',' . 
                                 $db->qstr($CustomFacultyProfileSupplement->imageFilename2) . ',' .
                                  $db->qstr($CustomFacultyProfileSupplement->url2) . ',' . 
                                 $db->qstr($CustomFacultyProfileSupplement->description2) . ',' .
								 
								 $db->qstr($CustomFacultyProfileSupplement->fp_name3) . ',' . 
                                 $db->qstr($CustomFacultyProfileSupplement->imageFilename3) . ',' .
                                  $db->qstr($CustomFacultyProfileSupplement->url3) . ',' . 
                                 $db->qstr($CustomFacultyProfileSupplement->description) . ')';
                $db->Execute($query);
                $id = $db->Insert_ID();
                
                if ($id > 0) {
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_FACULTY_PROFILE_TABLE);
                }
                
                return (int) $id;
        }

        /**
         * Update CustomFacultyProfileSupplement supplement database record.
         *
         * @param CustomFacultyProfileSupplement $CustomFacultyProfileSupplement An instance of CustomFacultyProfileSupplement with the details to update.
         */
        function updateCustomFacultyProfileSupplement($CustomFacultyProfileSupplement)
        {
                global $db;

                $query = 'UPDATE ' . CUSTOM_PAGE_SUPPLEMENT_FACULTY_PROFILE_TABLE . ' SET ' .
                                 
                                 'title = ' . $db->qstr($CustomFacultyProfileSupplement->title) . ',' .
                                                                 'fp_name = ' . $db->qstr($CustomFacultyProfileSupplement->fp_name) . ',' .
                                                                 'imageFilename = ' . $db->qstr($CustomFacultyProfileSupplement->imageFilename) . ',' .
                                                                 'description = ' . $db->qstr($CustomFacultyProfileSupplement->description) . ',' .
                                                                 'url = ' . $db->qstr($CustomFacultyProfileSupplement->url) . ',' .
                                                                                                                                 
                                                                                                                                 'fp_name2 = ' . $db->qstr($CustomFacultyProfileSupplement->fp_name2) . ',' .
                                                                 'imageFilename2 = ' . $db->qstr($CustomFacultyProfileSupplement->imageFilename2) . ',' .
                                                                 'description2 = ' . $db->qstr($CustomFacultyProfileSupplement->description2) . ',' .
                                                                 'url2 = ' . $db->qstr($CustomFacultyProfileSupplement->url2) . ',' .
                                                                                                                                 
                                                                 'fp_name3 = ' . $db->qstr($CustomFacultyProfileSupplement->fp_name3) . ',' .
                                                                 'imageFilename3 = ' . $db->qstr($CustomFacultyProfileSupplement->imageFilename3) . ',' .
                                                                 'description3 = ' . $db->qstr($CustomFacultyProfileSupplement->description3) . ',' .
                                                                 'url3 = ' . $db->qstr($CustomFacultyProfileSupplement->url3) . ' ' .
                                 'WHERE id = ' . intval($CustomFacultyProfileSupplement->id);

                $db->Execute($query);
                $affectedRows = $db->Affected_Rows();
                
                if ($affectedRows > 0) {
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_FACULTY_PROFILE_TABLE);
                }

                return (bool) $affectedRows;
        }

        /**
         * Delete CustomFacultyProfileSupplement in the database.
         *
         * @param integer $id The id of the CustomFacultyProfileSupplement to be deleted.
         */
        function deleteCustomFacultyProfileSupplement($id)
        {
                global $db;

                $query = 'DELETE FROM ' . CUSTOM_PAGE_SUPPLEMENT_FACULTY_PROFILE_TABLE . ' ' .
                                 'WHERE id = ' . intval($id);

                $db->Execute($query);
                $affectedRows = $db->Affected_Rows();
                
                if ($affectedRows > 0) {
                        // Delete all supplements referencing this record
                        deletePageSupplementForRecord($id);
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_FACULTY_PROFILE_TABLE);
                }

                return (bool) $affectedRows;
        }

        /**
         * A function to return an array of CustomFacultyProfileSupplement objects.
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @param array[]string $order List of fields to order results by
         * @param integer $limit The number of records to return (null = all)
         * @param integer $offset The position in the result set to begin returning records from
         * @return array[]CustomFacultyProfileSupplement Returns an array of CustomFacultyProfileSupplement objects.
         */
        function getAllCustomFacultyProfileSupplements($criteria = array(), $order = array(), $limit = null, $offset = null)
        {
                $query = buildCustomFacultyProfileSupplementQuery($criteria, $order);
                return fetchAllCustomFacultyProfileSupplementsWithQuery($query, $limit, $offset);
        }
        
        /**
         * Returns the number of CustomFacultyProfileSupplement supplements that match a given criteria
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @return integer The number of CustomFacultyProfileSupplement matching the given criteria
         */
        function countCustomFacultyProfileSupplements($criteria = array())
        {
                global $db;
                
                $query = buildCustomFacultyProfileSupplementQuery($criteria, array(), true);
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_FACULTY_PROFILE_TABLE, $query);
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
         * Get CustomFacultyProfileSupplement record from the database.
         *
         * @param integer $id The id of the advertSupplement you want.
         * @return CustomFacultyProfileSupplement Returns an instance of CustomFacultyProfileSupplement.
         */
        function getCustomFacultyProfileSupplement($id)
        {
                $query = buildCustomFacultyProfileSupplementQuery(array('id' => $id));
                return fetchCustomFacultyProfileSupplementWithQuery($query);
        }
        
        /**
         * Get a CustomFacultyProfileSupplement record from the database by title
         *
         * @param string $title The title of the advertSupplement you want.
         * @return CustomFacultyProfileSupplement Returns an instance of CustomFacultyProfileSupplement.
         */
        function getCustomFacultyProfileSupplementByTitle($title)
        {
                $query = buildCustomFacultyProfileSupplementQuery(array('title' => $title));
                return fetchCustomFacultyProfileSupplementWithQuery($query);
        }

        /**
        * Check that a CustomImageSupplement record is suitable for adding to the database.
        *
        * @param CustomFacultyProfileSupplement $customImageSupplement An instance of CustomImageSupplement
        * @return array An associative array with the fields that contain errors
        */
        function validateCustomFacultyProfileSupplement($customImageSupplement)
        {
                $errors = array();

                if (empty($customImageSupplement->title)) {
                        $errors['title'] = true;
                }
                else {
                        $numSupplements = countCustomFacultyProfileSupplements(array(
                                'title' => $customImageSupplement->title,
                                'except' => $customImageSupplement->id
                        ));
                        
                        if ($numSupplements > 0) {
                                // Title must be unique
                                $errors['title'] = true;
                        }
                }
                
                 if (empty($customImageSupplement->fp_name)) {
                        $errors['fp_name'] = true;
                }

                if (empty($customImageSupplement->imageFilename)) {
                        $errors['imageFilename'] = true;
                }

                                if (empty($customImageSupplement->url)) {
                        $errors['url'] = true;
                }

                                 if (empty($customImageSupplement->description)) {
                        $errors['description'] = true;
                  }
				  
				  
				 if (empty($customImageSupplement->fp_name2)) {
                        $errors['fp_name2'] = true;
                }

                if (empty($customImageSupplement->imageFilename2)) {
                        $errors['imageFilename2'] = true;
                }

                                if (empty($customImageSupplement->url2)) {
                        $errors['url2'] = true;
                }

                                 if (empty($customImageSupplement->description2)) {
                        $errors['description2'] = true;
                  } 


					 if (empty($customImageSupplement->fp_name3)) {
                        $errors['fp_name3'] = true;
                }

                if (empty($customImageSupplement->imageFilename3)) {
                        $errors['imageFilename3'] = true;
                }

                                if (empty($customImageSupplement->url3)) {
                        $errors['url3'] = true;
                }

                                 if (empty($customImageSupplement->description3)) {
                        $errors['description3'] = true;
                  }
                return $errors;
        }
?>


