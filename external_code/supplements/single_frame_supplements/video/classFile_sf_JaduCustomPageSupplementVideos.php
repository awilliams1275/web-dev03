<?php
        /** classFile "JaduCustomPageSupplementVideos.php"
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
        define("CUSTOM_PAGE_SUPPLEMENT_VIDEOS_TABLE", "JaduCustomPageSupplementVideos");

        include_once("JaduADODB.php");
        include_once("JaduCache.php");
        include_once('websections/JaduPageSupplements.php');

        /**
        * The CustomVideosSupplement class contains a set of fields relating to a CustomVideosSupplement.
        *
        * @package custom
        */
       class CustomVideosSupplement
        {
                /**
                * The id of the {@link CustomVideosSupplement} in the database.
                * @access public
                * @var integer
                */              
                var $id = -1;
        
                /**
                * The title of this {@link CustomVideosSupplement}
                * @access public
                * @var string
                */      
                var $title = '';

                                /**
                * The title of this {@link CustomVideosSupplement}
                * @access public
                * @var string
                */      
                var $left_head = '';

                                /**
                * The title of this {@link CustomVideosSupplement}
                * @access public
                * @var string
                */      
                var $right_head = '';

                                /**
                * The title of this {@link CustomVideosSupplement}
                * @access public
                * @var string
                */
                var $right_head_url = '';

                                /**
                * The title of this {@link CustomVideosSupplement}
                * @access public
                * @var string
                */      
                var $videos_b_1 = '';

                                /**
                * The title of this {@link CustomVideosSupplement}
                * @access public
                * @var string
                */      
                var $videourl_b_1 = '';

                                /**
                * The title of this {@link CustomVideosSupplement}
                * @access public
                * @var string
                */      
                var $videoimg_b_1 = '';
             
        }
        
        /**
         * Build the SQL query for the given criteria.
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @param array[]string $order List of fields to order results by
         * @param boolean $count Whether the query is a COUNT query
         * @return string The generated SQL query
         */
        function buildCustomVideosSupplementQuery($criteria = array(), $order = array(),
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
                        $query = 'SELECT id, title, left_head, right_head, right_head_url, videos_b_1, videourl_b_1, videoimg_b_1 ';
                        
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

                $query .= 'FROM ' . CUSTOM_PAGE_SUPPLEMENT_VIDEOS_TABLE . ' ' .
                                  $whereClause . 
                                  $orderClause;
                
                return $query;
        }
        
        /**
         * Fetch a single CustomVideosSupplement item using the given query.
         *
         * @param string $query The query to execute.
         * @return CustomVideosSupplement A single matching CustomVideosSupplement or an empty CustomVideosSupplement object.
         */
        function fetchCustomVideosSupplementWithQuery($query)
        {
                global $db;
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_VIDEOS_TABLE, $query);
                if ($cache->isEmpty()) {
                        $supplement = new CustomVideosSupplement();
                        $result = $db->SelectLimit($query, 1);
                        if ($result && !$result->EOF) {
                                $supplement->id = (int) $result->fields['id'];
                                $supplement->title = $result->fields['title'];
                               $supplement->left_head = $result->fields['left_head'];
                                $supplement->right_head = $result->fields['right_head'];
                                $supplement->right_head_url = $result->fields['right_head_url'];
                                $supplement->videos_b_1 = $result->fields['videos_b_1'];
                                $supplement->videourl_b_1 = $result->fields['videourl_b_1'];
                                $supplement->videoimg_b_1 = $result->fields['videoimg_b_1'];
 
                               
                        }
                        
                        $cache->setData($supplement);
                        return $supplement;
                }
                
                return $cache->data;
        }
        
        /**
         * Fetch all CustomVideosSupplement for the given query.
         *
         * @param string $query The SQL query to execute
         * @param integer $limit The number of records to return (null = all)
         * @param integer $offset The position in the result set to begin returning records from
         * @return array[]CustomVideosSupplement Array of matching CustomVideosSupplement objects
         */
        function fetchAllCustomVideosSupplementsWithQuery($query, $limit = null, $offset = null)
        {
                global $db;
                
                $cacheId = $query;
                if ($limit !== null) {
                        $cacheId .= ' LIMIT ' . (int) $limit . ',' . (int) $offset;
                }
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_VIDEOS_TABLE, $cacheId);
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
                                        $supplement = new CustomVideosSupplement();
                                        $supplement->id = (int) $result->fields['id'];
                                     $supplement->title = $result->fields['title'];
                                        $supplement->left_head = $result->fields['left_head'];
                                        $supplement->right_head = $result->fields['right_head'];
                                         $supplement->right_head_url = $result->fields['right_head_url'];
                                        $supplement->videos_b_1 = $result->fields['videos_b_1'];
                                        $supplement->videourl_b_1 = $result->fields['videourl_b_1'];
                                        $supplement->videoimg_b_1 = $result->fields['videoimg_b_1'];

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
         * Creates a new CustomVideosSupplement supplement record in the database.
         *
         * @param CustomVideosSupplement $CustomVideosSupplement An instance of CustomVideosSupplement to add to the database.
         * @return integer The database id of the newly created record.
         */
        function newCustomVideosSupplement($CustomVideosSupplement)
        {
                global $db;

                $query = 'INSERT INTO ' . CUSTOM_PAGE_SUPPLEMENT_VIDEOS_TABLE . ' ' . 
       '(title, left_head, right_head, right_head_url, videos_b_1, videourl_b_1, videoimg_b_1) VALUES (' . 
                                 $db->qstr($CustomVideosSupplement->title) . ',' .
                                 $db->qstr($CustomVideosSupplement->left_head) . ',' . 
                                 $db->qstr($CustomVideosSupplement->right_head) . ',' .
                                 $db->qstr($CustomVideosSupplement->right_head_url) . ',' .
                                 $db->qstr($CustomVideosSupplement->videos_b_1) . ',' . 
                                 $db->qstr($CustomVideosSupplement->videourl_b_1) . ',' .
                                 $db->qstr($CustomVideosSupplement->videoimg_b_1) . ')';

                $db->Execute($query);
                $id = $db->Insert_ID();
                
                if ($id > 0) {
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_VIDEOS_TABLE);
                }
                
                return (int) $id;
        }

        /**
         * Update CustomVideosSupplement supplement database record.
         *
         * @param CustomVideosSupplement $CustomVideosSupplement An instance of CustomVideosSupplement with the details to update.
         */
        function updateCustomVideosSupplement($CustomVideosSupplement)
        {
                global $db;

                $query = 'UPDATE ' . CUSTOM_PAGE_SUPPLEMENT_VIDEOS_TABLE . ' SET ' .
                                 
                                 
                                   'title = ' . $db->qstr($CustomVideosSupplement->title) . ',' .
                                                                    'left_head = ' . $db->qstr($CustomVideosSupplement->left_head) . ',' .
                                                                 'right_head = ' . $db->qstr($CustomVideosSupplement->right_head) . ',' .
                                                                 'right_head_url = ' . $db->qstr($CustomVideosSupplement->right_head_url) . ',' .
                                                                 'videos_b_1 = ' . $db->qstr($CustomVideosSupplement->videos_b_1) . ',' .
                                                                 'videourl_b_1 = ' . $db->qstr($CustomVideosSupplement->videourl_b_1) . ',' .
                                                                 'videoimg_b_1 = ' . $db->qstr($CustomVideosSupplement->videoimg_b_1) . ' ' .
                                 'WHERE id = ' . intval($CustomVideosSupplement->id);

                $db->Execute($query);
                $affectedRows = $db->Affected_Rows();
                
                if ($affectedRows > 0) {
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_VIDEOS_TABLE);
                }

                return (bool) $affectedRows;
        }

        /**
         * Delete CustomVideosSupplement in the database.
         *
         * @param integer $id The id of the CustomVideosSupplement to be deleted.
         */
        function deleteCustomVideosSupplement($id)
        {
                global $db;

                $query = 'DELETE FROM ' . CUSTOM_PAGE_SUPPLEMENT_VIDEOS_TABLE . ' ' .
                                 'WHERE id = ' . intval($id);

                $db->Execute($query);
                $affectedRows = $db->Affected_Rows();
                
                if ($affectedRows > 0) {
                        // Delete all supplements referencing this record
                        deletePageSupplementForRecord($id);
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_VIDEOS_TABLE);
                }

                return (bool) $affectedRows;
        }

        /**
         * A function to return an array of CustomVideosSupplement objects.
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @param array[]string $order List of fields to order results by
         * @param integer $limit The number of records to return (null = all)
         * @param integer $offset The position in the result set to begin returning records from
         * @return array[]CustomVideosSupplement Returns an array of CustomVideosSupplement objects.
         */
        function getAllCustomVideosSupplements($criteria = array(), $order = array(), $limit = null, $offset = null)
        {
                $query = buildCustomVideosSupplementQuery($criteria, $order);
                return fetchAllCustomVideosSupplementsWithQuery($query, $limit, $offset);
        }
        
        /**
         * Returns the number of CustomVideosSupplement supplements that match a given criteria
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @return integer The number of CustomVideosSupplement matching the given criteria
         */
        function countCustomVideosSupplements($criteria = array())
        {
                global $db;
                
                $query = buildCustomVideosSupplementQuery($criteria, array(), true);
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_VIDEOS_TABLE, $query);
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
         * Get CustomVideosSupplement record from the database.
         *
         * @param integer $id The id of the advertSupplement you want.
         * @return CustomVideosSupplement Returns an instance of CustomVideosSupplement.
         */
        function getCustomVideosSupplement($id)
        {
                $query = buildCustomVideosSupplementQuery(array('id' => $id));
                return fetchCustomVideosSupplementWithQuery($query);
        }
        
        /**
         * Get a CustomVideosSupplement record from the database by title
         *
         * @param string $title The title of the advertSupplement you want.
         * @return CustomVideosSupplement Returns an instance of CustomVideosSupplement.
         */
        function getCustomVideosSupplementByTitle($title)
        {
                $query = buildCustomVideosSupplementQuery(array('title' => $title));
                return fetchCustomVideosSupplementWithQuery($query);
        }

        /**
        * Check that a CustomImageSupplement record is suitable for adding to the database.
        *
        * @param CustomVideosSupplement $customImageSupplement An instance of CustomImageSupplement
        * @return array An associative array with the fields that contain errors
        */
        function validateCustomVideosSupplement($customImageSupplement)
       {
                $errors = array();

                if (empty($customImageSupplement->title)) {
                        $errors['title'] = true;
                }
                else {
                        $numSupplements = countCustomVideosSupplements(array(
                                'title' => $customImageSupplement->title,
                                'except' => $customImageSupplement->id
                        ));
                        
                        if ($numSupplements > 0) {
                                // Title must be unique
                                $errors['title'] = true;
                        }
                }
                
                 if (empty($customImageSupplement->videourl_b_1)) {
                        $errors['videourl_b_1'] = true;
                }

                if (empty($customImageSupplement->videoimg_b_1)) {
                        $errors['videoimg_b_1'] = true;
                }

                return $errors;
        }

?>

