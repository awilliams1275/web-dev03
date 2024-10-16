<?php
        /** classFile "JaduCustomPageSupplementCuraPersonalis.php"
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
  define("CUSTOM_PAGE_SUPPLEMENT_CURA_PERSONALIS_TABLE", "JaduCustomPageSupplementCuraPersonalis");

        include_once("JaduADODB.php");
        include_once("JaduCache.php");
        include_once('websections/JaduPageSupplements.php');

        /**
        * The CustomCuraPersonalisSupplement class contains a set of fields relating to a CustomCuraPersonalisSupplement.
        *
        * @package custom
        */
        class CustomCuraPersonalisSupplement
        {
                /**
                * The id of the {@link CustomCuraPersonalisSupplement} in the database.
                * @access public
                * @var integer
                */              
                var $id = -1;
        
                /**
                * The title of this {@link CustomCuraPersonalisSupplement}
                * @access public
                * @var string
                */      
                var $title = '';

                                 /**
                * The Name of the person Profiled of this {@link CustomCuraPersonalisSupplement}
                * @access public
                * @var string
                */      
                var $fu_cura_text = '';

                /**
                * The filename of an image that is assigned to this {@link CustomCuraPersonalisSupplement}
                * @access public
                * @var string
                */      
                var $fu_cura_by = '';        
              
                /**
                * The URL assigned to the link in this {@link CustomCuraPersonalisSupplement}
                * @access public
                * @var string
                */              
                var $fu_cura_story = '';

                                /**
                * The DESCRIPTION assigned to the link in this {@link CustomCuraPersonalisSupplement}
                * @access public
                * @var string
                */              
                var $fu_cura_url = '';

        }
        
        /**
         * Build the SQL query for the given criteria.
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @param array[]string $order List of fields to order results by
         * @param boolean $count Whether the query is a COUNT query
         * @return string The generated SQL query
         */
        function buildCustomCuraPersonalisSupplementQuery($criteria = array(), $order = array(),
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
                        $query = 'SELECT id, title, fu_cura_text, fu_cura_by, fu_cura_story, fu_cura_url ';
                        
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

                $query .= 'FROM ' . CUSTOM_PAGE_SUPPLEMENT_CURA_PERSONALIS_TABLE . ' ' .
                                  $whereClause . 
                                  $orderClause;
                
                return $query;
        }
        
        /**
         * Fetch a single CustomCuraPersonalisSupplement item using the given query.
         *
         * @param string $query The query to execute.
         * @return CustomCuraPersonalisSupplement A single matching CustomCuraPersonalisSupplement or an empty CustomCuraPersonalisSupplement object.
         */
        function fetchCustomCuraPersonalisSupplementWithQuery($query)
        {
                global $db;
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_CURA_PERSONALIS_TABLE, $query);
                if ($cache->isEmpty()) {
                        $supplement = new CustomCuraPersonalisSupplement();
                        $result = $db->SelectLimit($query, 1);
                        if ($result && !$result->EOF) {
                                $supplement->id = (int) $result->fields['id'];
                                $supplement->title = $result->fields['title'];
                                $supplement->fu_cura_text = $result->fields['fu_cura_text'];
                                $supplement->fu_cura_by = $result->fields['fu_cura_by'];
                                $supplement->fu_cura_story = $result->fields['fu_cura_story'];
                                $supplement->fu_cura_url = $result->fields['fu_cura_url'];

                        }
                        
                        $cache->setData($supplement);
                        return $supplement;
                }
                
                return $cache->data;
        }
        
        /**
         * Fetch all CustomCuraPersonalisSupplement for the given query.
         *
         * @param string $query The SQL query to execute
         * @param integer $limit The number of records to return (null = all)
         * @param integer $offset The position in the result set to begin returning records from
         * @return array[]CustomCuraPersonalisSupplement Array of matching CustomCuraPersonalisSupplement objects
         */
        function fetchAllCustomCuraPersonalisSupplementsWithQuery($query, $limit = null, $offset = null)
        {
                global $db;
                
                $cacheId = $query;
                if ($limit !== null) {
                        $cacheId .= ' LIMIT ' . (int) $limit . ',' . (int) $offset;
                }
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_CURA_PERSONALIS_TABLE, $cacheId);
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
                                        $supplement = new CustomCuraPersonalisSupplement();
                                        $supplement->id = (int) $result->fields['id'];
                                        $supplement->title = $result->fields['title'];
                                        $supplement->fu_cura_text = $result->fields['fu_cura_text'];
                                        $supplement->fu_cura_by = $result->fields['fu_cura_by'];
                                        $supplement->fu_cura_story = $result->fields['fu_cura_story'];
                                        $supplement->fu_cura_url = $result->fields['fu_cura_url'];
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
         * Creates a new CustomCuraPersonalisSupplement supplement record in the database.
         *
         * @param CustomCuraPersonalisSupplement $CustomCuraPersonalisSupplement An instance of CustomCuraPersonalisSupplement to add to the database.
         * @return integer The database id of the newly created record.
         */
        function newCustomCuraPersonalisSupplement($CustomCuraPersonalisSupplement)
        {
                global $db;

                 $query = 'INSERT INTO ' . CUSTOM_PAGE_SUPPLEMENT_CURA_PERSONALIS_TABLE . ' ' . 
                                 '(title, fu_cura_text, fu_cura_by, fu_cura_story, fu_cura_url) VALUES (' . 
                                 $db->qstr($CustomCuraPersonalisSupplement->title) . ',' .
                                 $db->qstr($CustomCuraPersonalisSupplement->fu_cura_text) . ',' . 
                                 $db->qstr($CustomCuraPersonalisSupplement->fu_cura_by) . ',' .
                                  $db->qstr($CustomCuraPersonalisSupplement->fu_cura_story) . ',' . 
                                 $db->qstr($CustomCuraPersonalisSupplement->fu_cura_url) . ')';
                $db->Execute($query);
                $id = $db->Insert_ID();
                
                if ($id > 0) {
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_CURA_PERSONALIS_TABLE);
                }
                
                return (int) $id;
        }

        /**
         * Update CustomCuraPersonalisSupplement supplement database record.
         *
         * @param CustomCuraPersonalisSupplement $CustomCuraPersonalisSupplement An instance of CustomCuraPersonalisSupplement with the details to update.
         */
        function updateCustomCuraPersonalisSupplement($CustomCuraPersonalisSupplement)
        {
                global $db;

                $query = 'UPDATE ' . CUSTOM_PAGE_SUPPLEMENT_CURA_PERSONALIS_TABLE . ' SET ' .
                                 
                                 'title = ' . $db->qstr($CustomCuraPersonalisSupplement->title) . ',' .
                                                                 'fu_cura_text = ' . $db->qstr($CustomCuraPersonalisSupplement->fu_cura_text) . ',' .
                                                                 'fu_cura_by = ' . $db->qstr($CustomCuraPersonalisSupplement->fu_cura_by) . ',' .
                                                                 'fu_cura_story = ' . $db->qstr($CustomCuraPersonalisSupplement->fu_cura_story) . ',' .
                                                                 'fu_cura_url = ' . $db->qstr($CustomCuraPersonalisSupplement->fu_cura_url) . ' ' .
                                 'WHERE id = ' . intval($CustomCuraPersonalisSupplement->id);

                $db->Execute($query);
                $affectedRows = $db->Affected_Rows();
                
                if ($affectedRows > 0) {
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_CURA_PERSONALIS_TABLE);
                }

                return (bool) $affectedRows;
        }

        /**
         * Delete CustomCuraPersonalisSupplement in the database.
         *
         * @param integer $id The id of the CustomCuraPersonalisSupplement to be deleted.
         */
        function deleteCustomCuraPersonalisSupplement($id)
        {
                global $db;

                $query = 'DELETE FROM ' . CUSTOM_PAGE_SUPPLEMENT_CURA_PERSONALIS_TABLE . ' ' .
                                 'WHERE id = ' . intval($id);

                $db->Execute($query);
                $affectedRows = $db->Affected_Rows();
                
                if ($affectedRows > 0) {
                        // Delete all supplements referencing this record
                        deletePageSupplementForRecord($id);
                        deleteTableCache(CUSTOM_PAGE_SUPPLEMENT_CURA_PERSONALIS_TABLE);
                }

                return (bool) $affectedRows;
        }

        /**
         * A function to return an array of CustomCuraPersonalisSupplement objects.
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @param array[]string $order List of fields to order results by
         * @param integer $limit The number of records to return (null = all)
         * @param integer $offset The position in the result set to begin returning records from
         * @return array[]CustomCuraPersonalisSupplement Returns an array of CustomCuraPersonalisSupplement objects.
         */
        function getAllCustomCuraPersonalisSupplements($criteria = array(), $order = array(), $limit = null, $offset = null)
        {
                $query = buildCustomCuraPersonalisSupplementQuery($criteria, $order);
                return fetchAllCustomCuraPersonalisSupplementsWithQuery($query, $limit, $offset);
        }
        
        /**
         * Returns the number of CustomCuraPersonalisSupplement supplements that match a given criteria
         *
         * @param array[string]string $criteria Fields to search for as keys mapped to values to match against.
         * @return integer The number of CustomCuraPersonalisSupplement matching the given criteria
         */
        function countCustomCuraPersonalisSupplements($criteria = array())
        {
                global $db;
                
                $query = buildCustomCuraPersonalisSupplementQuery($criteria, array(), true);
                
                $cache = new Cache(CUSTOM_PAGE_SUPPLEMENT_CURA_PERSONALIS_TABLE, $query);
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
         * Get CustomCuraPersonalisSupplement record from the database.
         *
         * @param integer $id The id of the advertSupplement you want.
         * @return CustomCuraPersonalisSupplement Returns an instance of CustomCuraPersonalisSupplement.
         */
        function getCustomCuraPersonalisSupplement($id)
        {
                $query = buildCustomCuraPersonalisSupplementQuery(array('id' => $id));
                return fetchCustomCuraPersonalisSupplementWithQuery($query);
        }
        
        /**
         * Get a CustomCuraPersonalisSupplement record from the database by title
         *
         * @param string $title The title of the advertSupplement you want.
         * @return CustomCuraPersonalisSupplement Returns an instance of CustomCuraPersonalisSupplement.
         */
        function getCustomCuraPersonalisSupplementByTitle($title)
        {
                $query = buildCustomCuraPersonalisSupplementQuery(array('title' => $title));
                return fetchCustomCuraPersonalisSupplementWithQuery($query);
        }

        /**
        * Check that a CustomImageSupplement record is suitable for adding to the database.
        *
        * @param CustomCuraPersonalisSupplement $customImageSupplement An instance of CustomImageSupplement
        * @return array An associative array with the fields that contain errors
        */
        function validateCustomCuraPersonalisSupplement($customImageSupplement)
        {
                $errors = array();

                if (empty($customImageSupplement->title)) {
                        $errors['title'] = true;
                }
                else {
                        $numSupplements = countCustomCuraPersonalisSupplements(array(
                                'title' => $customImageSupplement->title,
                                'except' => $customImageSupplement->id
                        ));
                        
                        if ($numSupplements > 0) {
                                // Title must be unique
                                $errors['title'] = true;
                        }
                }
                
                 if (empty($customImageSupplement->fu_cura_text)) {
                        $errors['fu_cura_text'] = true;
                }

                if (empty($customImageSupplement->fu_cura_by)) {
                        $errors['fu_cura_by'] = true;
                }

  

                return $errors;
        }
?>


