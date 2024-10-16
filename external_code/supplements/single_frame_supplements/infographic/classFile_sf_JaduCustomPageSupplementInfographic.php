<?php
        /** classFile "JaduCustomPageSupplementInfographic.php"
        * physical location: /var/www/jadu/jadu/custom/
        * Custom Cura Personalis supplement widget.
        *
        * @package custom
        * @copyright All Contents (c) 2007 Jadu Ltd.
        * @author Jadu Ltd.
        */

        /**
        * The database table used to hold image links 
        */
        define("CUSTOM_PAGE_SUPPLEMENT_INFOGRAPHIC_TABLE", "JaduCustomPageSupplementInfographic");

        include_once("JaduADODB.php");
        include_once("JaduCache.php");
        include_once('websections/JaduPageSupplements.php');

        /**
        * The CustomInfographicSupplement class contains a set of fields relating to a CustomInfographicSupplement.
        *
        * @package custom
        */
       class CustomInfographicSupplement
        {
                /**
                * The id of the {@link CustomInfographicSupplement} in the database.
                * @access public
                * @var integer
                */              
                var $id = -1;
        
                /**
                * The title of this {@link CustomInfographicSupplement}
                * @access public
                * @var string
                */      
                var $title = '';

                                /**
                * The title of this {@link CustomInfographicSupplement}
                * @access public
                * @var string
                */      
                var $fu_fbtn_2_text = '';

                                /**
                * The title of this {@link CustomInfographicSupplement}
                * @access public
                * @var string
                */      
                var $fu_fbtn_2 = '';

                                /**
                * The title of this {@link CustomInfographicSupplement}
                * @access public
                * @var string
                */
                var $imageFilename = '';

                                /**
                * The title of this {@link CustomInfographicSupplement}
                * @access public
                * @var string
                */      
                var $imageFilename2 = '';

                                /**
                * The title of this {@link CustomInfographicSupplement}
                * @access public
                * @var string
                */      
                var $fu_img_text1 = '';
             
        }
        
        /**
         * Build the SQL query for the given criteria.
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @param array[]string $order List of fields to order results by
         * @param boolean $count Whether the query is a COUNT query
         * @return string The generated SQL query
         */
        function buildCustomInfographicSupplementQuery($criteria = array(), $order = array(),
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
                        $query = 'SELECT id, title, fu_fbtn_2_text, fu_fbtn_2, imageFilename, imageFilename2, fu_img_text1 ';
                        
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

                $query .= 'FROM ' . CUSTOM_PAGE_SUPPLEMENT_INFOGRAPHIC_TABLE . ' ' .
                                  $whereClause . 
                                  $orderClause;
                
                return $query;
        }
        
        /**
         * Fetch a single CustomInfographicSupplement item using the given query.
         *
         * @param string $query The query to execute.
         * @return CustomInfographicSupplement A single matching CustomInfographicSupplement or an empty CustomInfographicSupplement object.
         */
        function fetchCustomInfographicSupplementWithQuery($query)
        {
                global $db;
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_INFOGRAPHIC_TABLE, $query);
                if ($cache->isEmpty()) {
                        $supplement = new CustomInfographicSupplement();
                        $result = $db->SelectLimit($query, 1);
                        if ($result && !$result->EOF) {
                                $supplement->id = (int) $result->fields['id'];
                                $supplement->title = $result->fields['title'];
                               $supplement->fu_fbtn_2_text = $result->fields['fu_fbtn_2_text'];
                                $supplement->fu_fbtn_2 = $result->fields['fu_fbtn_2'];
                                $supplement->imageFilename = $result->fields['imageFilename'];
                                $supplement->imageFilename2 = $result->fields['imageFilename2'];
                                $supplement->fu_img_text1 = $result->fields['fu_img_text1'];
                                
                        }
                        
                        $cache->setData($supplement);
                        return $supplement;
                }
                
                return $cache->data;
        }
        
        /**
         * Fetch all CustomInfographicSupplement for the given query.
         *
         * @param string $query The SQL query to execute
         * @param integer $limit The number of records to return (null = all)
         * @param integer $offset The position in the result set to begin returning records from
         * @return array[]CustomInfographicSupplement Array of matching CustomInfographicSupplement objects
         */
        function fetchAllCustomInfographicSupplementsWithQuery($query, $limit = null, $offset = null)
        {
                global $db;
                
                $cacheId = $query;
                if ($limit !== null) {
                        $cacheId .= ' LIMIT ' . (int) $limit . ',' . (int) $offset;
                }
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_INFOGRAPHIC_TABLE, $cacheId);
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
                                        $supplement = new CustomInfographicSupplement();
                                        $supplement->id = (int) $result->fields['id'];
                                     $supplement->title = $result->fields['title'];
                                        $supplement->fu_fbtn_2_text = $result->fields['fu_fbtn_2_text'];
                                        $supplement->fu_fbtn_2 = $result->fields['fu_fbtn_2'];
                                         $supplement->imageFilename = $result->fields['imageFilename'];
                                        $supplement->imageFilename2 = $result->fields['imageFilename2'];
                                        $supplement->fu_img_text1 = $result->fields['fu_img_text1'];
                                        

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
         * Creates a new CustomInfographicSupplement supplement record in the database.
         *
         * @param CustomInfographicSupplement $CustomInfographicSupplement An instance of CustomInfographicSupplement to add to the database.
         * @return integer The database id of the newly created record.
         */
        function newCustomInfographicSupplement($CustomInfographicSupplement)
        {
                global $db;

                $query = 'INSERT INTO ' . CUSTOM_PAGE_SUPPLEMENT_INFOGRAPHIC_TABLE . ' ' . 
       '(title, fu_fbtn_2_text, fu_fbtn_2, imageFilename, imageFilename2, fu_img_text1) VALUES (' . 
                                 $db->qstr($CustomInfographicSupplement->title) . ',' .
                                 $db->qstr($CustomInfographicSupplement->fu_fbtn_2_text) . ',' . 
                                 $db->qstr($CustomInfographicSupplement->fu_fbtn_2) . ',' .
                                 $db->qstr($CustomInfographicSupplement->imageFilename) . ',' .
                                 $db->qstr($CustomInfographicSupplement->imageFilename2) . ',' . 
                                 $db->qstr($CustomInfographicSupplement->fu_img_text1) . ')';

                $db->Execute($query);
                $id = $db->Insert_ID();
                
                if ($id > 0) {
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_INFOGRAPHIC_TABLE);
                }
                
                return (int) $id;
        }

        /**
         * Update CustomInfographicSupplement supplement database record.
         *
         * @param CustomInfographicSupplement $CustomInfographicSupplement An instance of CustomInfographicSupplement with the details to update.
         */
        function updateCustomInfographicSupplement($CustomInfographicSupplement)
        {
                global $db;

                $query = 'UPDATE ' . CUSTOM_PAGE_SUPPLEMENT_INFOGRAPHIC_TABLE . ' SET ' .
                                 
                                 
                                   'title = ' . $db->qstr($CustomInfographicSupplement->title) . ',' .
                                                                    'fu_fbtn_2_text = ' . $db->qstr($CustomInfographicSupplement->fu_fbtn_2_text) . ',' .
                                                                 'fu_fbtn_2 = ' . $db->qstr($CustomInfographicSupplement->fu_fbtn_2) . ',' .
                                                                 'imageFilename = ' . $db->qstr($CustomInfographicSupplement->imageFilename) . ',' .
                                                                 'imageFilename2 = ' . $db->qstr($CustomInfographicSupplement->imageFilename2) . ',' .
                                                                 'fu_img_text1 = ' . $db->qstr($CustomInfographicSupplement->fu_img_text1) . ' ' .
                                 'WHERE id = ' . intval($CustomInfographicSupplement->id);

                $db->Execute($query);
                $affectedRows = $db->Affected_Rows();
                
                if ($affectedRows > 0) {
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_INFOGRAPHIC_TABLE);
                }

                return (bool) $affectedRows;
        }

        /**
         * Delete CustomInfographicSupplement in the database.
         *
         * @param integer $id The id of the CustomInfographicSupplement to be deleted.
         */
        function deleteCustomInfographicSupplement($id)
        {
                global $db;

                $query = 'DELETE FROM ' . CUSTOM_PAGE_SUPPLEMENT_INFOGRAPHIC_TABLE . ' ' .
                                 'WHERE id = ' . intval($id);

                $db->Execute($query);
                $affectedRows = $db->Affected_Rows();
                
                if ($affectedRows > 0) {
                        // Delete all supplements referencing this record
                        deletePageSupplementForRecord($id);
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_INFOGRAPHIC_TABLE);
                }

                return (bool) $affectedRows;
        }

        /**
         * A function to return an array of CustomInfographicSupplement objects.
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @param array[]string $order List of fields to order results by
         * @param integer $limit The number of records to return (null = all)
         * @param integer $offset The position in the result set to begin returning records from
         * @return array[]CustomInfographicSupplement Returns an array of CustomInfographicSupplement objects.
         */
        function getAllCustomInfographicSupplements($criteria = array(), $order = array(), $limit = null, $offset = null)
        {
                $query = buildCustomInfographicSupplementQuery($criteria, $order);
                return fetchAllCustomInfographicSupplementsWithQuery($query, $limit, $offset);
        }
        
        /**
         * Returns the number of CustomInfographicSupplement supplements that match a given criteria
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @return integer The number of CustomInfographicSupplement matching the given criteria
         */
        function countCustomInfographicSupplements($criteria = array())
        {
                global $db;
                
                $query = buildCustomInfographicSupplementQuery($criteria, array(), true);
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_INFOGRAPHIC_TABLE, $query);
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
         * Get CustomInfographicSupplement record from the database.
         *
         * @param integer $id The id of the advertSupplement you want.
         * @return CustomInfographicSupplement Returns an instance of CustomInfographicSupplement.
         */
        function getCustomInfographicSupplement($id)
        {
                $query = buildCustomInfographicSupplementQuery(array('id' => $id));
                return fetchCustomInfographicSupplementWithQuery($query);
        }
        
        /**
         * Get a CustomInfographicSupplement record from the database by title
         *
         * @param string $title The title of the advertSupplement you want.
         * @return CustomInfographicSupplement Returns an instance of CustomInfographicSupplement.
         */
        function getCustomInfographicSupplementByTitle($title)
        {
                $query = buildCustomInfographicSupplementQuery(array('title' => $title));
                return fetchCustomInfographicSupplementWithQuery($query);
        }

        /**
        * Check that a CustomImageSupplement record is suitable for adding to the database.
        *
        * @param CustomInfographicSupplement $customImageSupplement An instance of CustomImageSupplement
        * @return array An associative array with the fields that contain errors
        */
        function validateCustomInfographicSupplement($customImageSupplement)
       {
                $errors = array();

                if (empty($customImageSupplement->title)) {
                        $errors['title'] = true;
                }
                else {
                        $numSupplements = countCustomInfographicSupplements(array(
                                'title' => $customImageSupplement->title,
                                'except' => $customImageSupplement->id
                        ));
                        
                        if ($numSupplements > 0) {
                                // Title must be unique
                                $errors['title'] = true;
                        }
                }
                
                 if (empty($customImageSupplement->imageFilename)) {
                        $errors['imageFilename'] = true;
                }

                if (empty($customImageSupplement->imageFilename2)) {
                        $errors['imageFilename2'] = true;
                }
				
				 if (empty($customImageSupplement->fu_img_text1)) {
                        $errors['fu_img_text1'] = true;
                }

                return $errors;
        }

?>

