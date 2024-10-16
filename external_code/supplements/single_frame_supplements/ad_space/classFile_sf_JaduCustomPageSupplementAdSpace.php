<?php
        /** classFile "JaduCustomPageSupplementAdSpace.php"
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
        define("CUSTOM_PAGE_SUPPLEMENT_AD_SPACE_TABLE", "JaduCustomPageSupplementAdSpace");

        include_once("JaduADODB.php");
        include_once("JaduCache.php");
        include_once('websections/JaduPageSupplements.php');

        /**
        * The CustomAdSpaceSupplement class contains a set of fields relating to a CustomAdSpaceSupplement.
        *
        * @package custom
        */
        class CustomAdSpaceSupplement
        {
                /**
                * The id of the {@link CustomAdSpaceSupplement} in the database.
                * @access public
                * @var integer
                */              
                var $id = -1;
        
                /**
                * The title of this {@link CustomAdSpaceSupplement}
                * @access public
                * @var string
                */      
                var $title = '';

                /**
                * The Name of the person Profiled of this {@link CustomAdSpaceSupplement}
                * @access public
                * @var string
                */      
                var $fu_ad_title1 = '';

                                /**
                * The Name of the person Profiled of this {@link CustomAdSpaceSupplement}
                * @access public
                * @var string
                */      
                var $fu_ad_title2 = '';

                                /**
                * The Name of the person Profiled of this {@link CustomAdSpaceSupplement}
                * @access public
                * @var string
                */      
                var $fu_ad_title_link = '';

                                /**
                * The Name of the person Profiled of this {@link CustomAdSpaceSupplement}
                * @access public
                * @var string
                */      
                var $fu_ad_src1a = '';

                                /**
                * The Name of the person Profiled of this {@link CustomAdSpaceSupplement}
                * @access public
                * @var string
                */      
                var $fu_ad_head = '';

                                /**
                * The Name of the person Profiled of this {@link CustomAdSpaceSupplement}
                * @access public
                * @var string
                */      
                var $fu_ad_text = '';

                                /**
                * The Name of the person Profiled of this {@link CustomAdSpaceSupplement}
                * @access public
                * @var string
                */      
                var $fu_ad_tag = '';

                                                                /**
                * The Name of the person Profiled of this {@link CustomAdSpaceSupplement}
                * @access public
                * @var string
                */      
                var $fu_ad_tag_url = '';

        }
        
        /**
         * Build the SQL query for the given criteria.
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @param array[]string $order List of fields to order results by
         * @param boolean $count Whether the query is a COUNT query
         * @return string The generated SQL query
         */
        function buildCustomAdSpaceSupplementQuery($criteria = array(), $order = array(),
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
                        $query = 'SELECT id, title, fu_ad_title1, fu_ad_title2, fu_ad_title_link, fu_ad_src1a, fu_ad_head, fu_ad_text, fu_ad_tag, fu_ad_tag_url ';
                        
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

                $query .= 'FROM ' . CUSTOM_PAGE_SUPPLEMENT_AD_SPACE_TABLE . ' ' .
                                  $whereClause . 
                                  $orderClause;
                
                return $query;
        }
        
        /**
         * Fetch a single CustomAdSpaceSupplement item using the given query.
         *
         * @param string $query The query to execute.
         * @return CustomAdSpaceSupplement A single matching CustomAdSpaceSupplement or an empty CustomAdSpaceSupplement object.
         */
        function fetchCustomAdSpaceSupplementWithQuery($query)
        {
                global $db;
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_AD_SPACE_TABLE, $query);
                if ($cache->isEmpty()) {
                        $supplement = new CustomAdSpaceSupplement();
                        $result = $db->SelectLimit($query, 1);
                        if ($result && !$result->EOF) {
                                $supplement->id = (int) $result->fields['id'];
                                $supplement->title = $result->fields['title'];
                                $supplement->fu_ad_title1 = $result->fields['fu_ad_title1'];
                                                                $supplement->fu_ad_title2 = $result->fields['fu_ad_title2'];
                                                                $supplement->fu_ad_title_link = $result->fields['fu_ad_title_link'];
                                                                $supplement->fu_ad_src1a = $result->fields['fu_ad_src1a'];
                                                                $supplement->fu_ad_head = $result->fields['fu_ad_head'];
                                                                $supplement->fu_ad_text = $result->fields['fu_ad_text'];
                                                                $supplement->fu_ad_tag = $result->fields['fu_ad_tag'];
                               $supplement->fu_ad_tag_url = $result->fields['fu_ad_tag_url'];
                        }
                        
                        $cache->setData($supplement);
                        return $supplement;
                }
                
                return $cache->data;
        }
        
        /**
         * Fetch all CustomAdSpaceSupplement for the given query.
         *
         * @param string $query The SQL query to execute
         * @param integer $limit The number of records to return (null = all)
         * @param integer $offset The position in the result set to begin returning records from
         * @return array[]CustomAdSpaceSupplement Array of matching CustomAdSpaceSupplement objects
         */
        function fetchAllCustomAdSpaceSupplementsWithQuery($query, $limit = null, $offset = null)
        {
                global $db;
                
                $cacheId = $query;
                if ($limit !== null) {
                        $cacheId .= ' LIMIT ' . (int) $limit . ',' . (int) $offset;
                }
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_AD_SPACE_TABLE, $cacheId);
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
                                        $supplement = new CustomAdSpaceSupplement();
                                        $supplement->id = (int) $result->fields['id'];
                                        $supplement->title = $result->fields['title'];
                                        $supplement->fu_ad_title1 = $result->fields['fu_ad_title1'];
                                        $supplement->fu_ad_title2 = $result->fields['fu_ad_title2'];
                                                                                $supplement->fu_ad_title_link = $result->fields['fu_ad_title_link'];
                                                                                $supplement->fu_ad_src1a = $result->fields['fu_ad_src1a'];
                                                                                $supplement->fu_ad_head = $result->fields['fu_ad_head'];
                                                                                $supplement->fu_ad_text = $result->fields['fu_ad_text'];
                                                                                $supplement->fu_ad_tag = $result->fields['fu_ad_tag'];
                                                                                $supplement->fu_ad_tag_url = $result->fields['fu_ad_tag_url'];
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
         * Creates a new CustomAdSpaceSupplement supplement record in the database.
         *
         * @param CustomAdSpaceSupplement $CustomAdSpaceSupplement An instance of CustomAdSpaceSupplement to add to the database.
         * @return integer The database id of the newly created record.
         */
        function newCustomAdSpaceSupplement($CustomAdSpaceSupplement)
        {
                global $db;

                $query = 'INSERT INTO ' . CUSTOM_PAGE_SUPPLEMENT_AD_SPACE_TABLE . ' ' . 
                                 '(title, fu_ad_title1, fu_ad_title2, fu_ad_title_link, fu_ad_src1a, fu_ad_head, fu_ad_text, fu_ad_tag, fu_ad_tag_url) VALUES (' . 
                                 $db->qstr($CustomAdSpaceSupplement->title) . ',' .
                                                                 $db->qstr($CustomAdSpaceSupplement->fu_ad_title1) . ',' .
                                                                 $db->qstr($CustomAdSpaceSupplement->fu_ad_title2) . ',' .
                                                                 $db->qstr($CustomAdSpaceSupplement->fu_ad_title_link) . ',' .
                                                                 $db->qstr($CustomAdSpaceSupplement->fu_ad_src1a) . ',' .
                                                                 $db->qstr($CustomAdSpaceSupplement->fu_ad_head) . ',' .
                                                                 $db->qstr($CustomAdSpaceSupplement->fu_ad_text) . ',' .
                                                                 $db->qstr($CustomAdSpaceSupplement->fu_ad_tag) . ',' .
                                                                 $db->qstr($CustomAdSpaceSupplement->fu_ad_tag_url) . ')';

                $db->Execute($query);
                $id = $db->Insert_ID();
                
                if ($id > 0) {
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_AD_SPACE_TABLE);
                }
                
                return (int) $id;
        }

        /**
         * Update CustomAdSpaceSupplement supplement database record.
         *
         * @param CustomAdSpaceSupplement $CustomAdSpaceSupplement An instance of CustomAdSpaceSupplement with the details to update.
         */
        function updateCustomAdSpaceSupplement($CustomAdSpaceSupplement)
        {
                global $db;

                $query = 'UPDATE ' . CUSTOM_PAGE_SUPPLEMENT_AD_SPACE_TABLE . ' SET ' .
                                 
                                 'title = ' . $db->qstr($CustomAdSpaceSupplement->title) . ',' .
                                 'fu_ad_title1 = ' . $db->qstr($CustomAdSpaceSupplement->fu_ad_title1) . ',' .
                                                                 'fu_ad_title2 = ' . $db->qstr($CustomAdSpaceSupplement->fu_ad_title2) . ',' .
                                                                 'fu_ad_title_link = ' . $db->qstr($CustomAdSpaceSupplement->fu_ad_title_link) . ',' .
                                                                 'fu_ad_src1a = ' . $db->qstr($CustomAdSpaceSupplement->fu_ad_src1a) . ',' .
                                                                 'fu_ad_head = ' . $db->qstr($CustomAdSpaceSupplement->fu_ad_head) . ',' .
                                                                 'fu_ad_text = ' . $db->qstr($CustomAdSpaceSupplement->fu_ad_text) . ',' .
                                                                 'fu_ad_tag = ' . $db->qstr($CustomAdSpaceSupplement->fu_ad_tag) . ',' .
                                                                 'fu_ad_tag_url = ' . $db->qstr($CustomAdSpaceSupplement->fu_ad_tag_url) . ' ' .
                                 'WHERE id = ' . intval($CustomAdSpaceSupplement->id);

                $db->Execute($query);
                $affectedRows = $db->Affected_Rows();
                
                if ($affectedRows > 0) {
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_AD_SPACE_TABLE);
                }

                return (bool) $affectedRows;
        }

        /**
         * Delete CustomAdSpaceSupplement in the database.
         *
         * @param integer $id The id of the CustomAdSpaceSupplement to be deleted.
         */
        function deleteCustomAdSpaceSupplement($id)
        {
                global $db;

                $query = 'DELETE FROM ' . CUSTOM_PAGE_SUPPLEMENT_AD_SPACE_TABLE . ' ' .
                                 'WHERE id = ' . intval($id);

                $db->Execute($query);
                $affectedRows = $db->Affected_Rows();
                
                if ($affectedRows > 0) {
                        // Delete all supplements referencing this record
                        deletePageSupplementForRecord($id);
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_AD_SPACE_TABLE);
                }

                return (bool) $affectedRows;
        }

        /**
         * A function to return an array of CustomAdSpaceSupplement objects.
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @param array[]string $order List of fields to order results by
         * @param integer $limit The number of records to return (null = all)
         * @param integer $offset The position in the result set to begin returning records from
         * @return array[]CustomAdSpaceSupplement Returns an array of CustomAdSpaceSupplement objects.
         */
        function getAllCustomAdSpaceSupplements($criteria = array(), $order = array(), $limit = null, $offset = null)
        {
                $query = buildCustomAdSpaceSupplementQuery($criteria, $order);
                return fetchAllCustomAdSpaceSupplementsWithQuery($query, $limit, $offset);
        }
        
        /**
         * Returns the number of CustomAdSpaceSupplement supplements that match a given criteria
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @return integer The number of CustomAdSpaceSupplement matching the given criteria
         */
        function countCustomAdSpaceSupplements($criteria = array())
        {
                global $db;
                
                $query = buildCustomAdSpaceSupplementQuery($criteria, array(), true);
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_AD_SPACE_TABLE, $query);
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
         * Get CustomAdSpaceSupplement record from the database.
         *
         * @param integer $id The id of the advertSupplement you want.
         * @return CustomAdSpaceSupplement Returns an instance of CustomAdSpaceSupplement.
         */
        function getCustomAdSpaceSupplement($id)
        {
                $query = buildCustomAdSpaceSupplementQuery(array('id' => $id));
                return fetchCustomAdSpaceSupplementWithQuery($query);
        }
        
        /**
         * Get a CustomAdSpaceSupplement record from the database by title
         *
         * @param string $title The title of the advertSupplement you want.
         * @return CustomAdSpaceSupplement Returns an instance of CustomAdSpaceSupplement.
         */
        function getCustomAdSpaceSupplementByTitle($title)
        {
                $query = buildCustomAdSpaceSupplementQuery(array('title' => $title));
                return fetchCustomAdSpaceSupplementWithQuery($query);
        }

        /**
        * Check that a CustomImageSupplement record is suitable for adding to the database.
        *
        * @param CustomAdSpaceSupplement $customImageSupplement An instance of CustomImageSupplement
        * @return array An associative array with the fields that contain errors
        */
        function validateCustomAdSpaceSupplement($customImageSupplement)
        {
                $errors = array();

                if (empty($customImageSupplement->title)) {
                        $errors['title'] = true;
                }
                else {
                        $numSupplements = countCustomAdSpaceSupplements(array(
                                'title' => $customImageSupplement->title,
                                'except' => $customImageSupplement->id
                        ));
                        
                        if ($numSupplements > 0) {
                                // Title must be unique
                                $errors['title'] = true;
                        }
                }
                
                                 if (empty($customImageSupplement->fu_ad_src1a)) {
                        $errors['fu_ad_src1a'] = true;
                }

                                 if (empty($customImageSupplement->fu_ad_head)) {
                        $errors['fu_ad_head'] = true;
                }

                                 if (empty($customImageSupplement->fu_ad_text)) {
                        $errors['fu_ad_text'] = true;
                }

                return $errors;
        }
?>


