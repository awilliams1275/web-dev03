<?php
	/**
	 * JaduHomepageWidgetWidgets.php contains a class and functions to
	 * manipulate the widgets that can be placed on a homepage.
	 *
	 * A widget consists of two scripts (for front-end and Control Centre)
	 * that are both stored in the database. Widgets also have a number of
	 * other properties determining how they behave and some metadata.
	 *
	 * @package publishing
	 * @copyright All Contents (c) 2007 Jadu Ltd.
	 * @author Jadu Ltd.
	 */
	
	/**
	 * The database table used to hold records for homepage widgets.
	 */
	define("HOMEPAGE_WIDGETS_TABLE", "JaduHomepageWidgets");
	
	define("WIDGET_USE_NO_TAXONOMY", 0);
	define("WIDGET_USE_MAIN_TAXONOMY", 1);
	define("WIDGET_USE_IPSV_TAXONOMY", 2);
	define("WIDGET_USE_RETAIL_TAXONOMY", 4);
	
	require_once("JaduADODB.php");
	require_once("JaduImages.php");
	require_once("utilities/JaduAdminPageActions.php");
	require_once("utilities/JaduReadableURLs.php");
	require_once("galaxies/JaduGalaxiesSites.php");
	require_once("ext/json.php");
	
	/**
	 * The HomepageWidgetWidget class contains the details for a single homepage widget.
	 *
	 * Each object variable has an equivilent field in the database
	 *
	 * @package publishing
	 */
	class HomepageWidget
	{
		const WIDGET_SCOPE_ALL_SITES = 0;
		const WIDGET_SCOPE_THIS_SITE = 1;
		const WIDGET_SCOPE_GALAXY_ONLY = 2;
		const WIDGET_SCOPE_ENTERPRISE_SITES = 3;		
		
		/**
			* The id of the {@link HomepageWidget} in the database
			* @var integer
			* @access public
			*/
		var $id;
		
		/**
			* The internalID of the {@link HomepageWidget} in the database
			* @var integer
			* @access public
			*/
		var $internalID;	
		
		/**
			* The title of the {@link HomepageWidget}
			* @var string
			* @access public
			*/
		var $title;
		/**
			* The default percentage width a widget should have when dragged into the control centre homepages designer
			* @var integer
			* @access public
			*/
		var $defaultWidthPercentage;
		/**
			* The default background color of the widget as displayed on the front end
			* @var string
			* @access public
			*/
		var $defaultBackgroundColour;
		/**
			* Description of the {@link HomepageWidget} front end
			* @var string
			* @access public
			*/
		var $description;
		/**
			* The filename of the widget used as a preview for the {@link HomepageWidget}
			* @var string
			* @access public
			*/
		var $previewImage;
		/**
			* The server side code used to display the widget on the front end
			* @var string
			* @access public
			*/
		var $contentCode;
		/**
			* The 'code behind' code used in .NET widgets, stores ascx.cs contents
			* @var string
			* @access public
			*/
		var $contentCodeBehind;
		/**
			* The client side javascript code used for the front end
			* @var string
			* @access public
			*/
		var $contentJs;
		/**
			* Server side code for the control centre settings page for the widget
			* @var string
			* @access public
			*/
		var $settingsCode;
		/**
			* Client side javascript code for the control centre settings page for the widget
			* @var string
			* @access public
			*/
		var $jsCode;
		/**
			* Whether this widget requires a category assigning [0 - Does not require a category], [1 - Does require a category]
			* @var integer
			* @access public
			*/
		var $requiresCategory;
		/**
			* The minimum width as a percentage that this widget can be re-sized to in the control centre
			* @var integer
			* @access public
			*/
		var $minimumWidth;
		/**
			* Whether use is limited
			* @var integer
			* @access public
			*/
		var $limitUse;
		/**
			* Whether only a signed in member of the site can see the contents of this widget [0 - Non members allowed], [1 - Only members allowed]
			* @var integer
			* @access public
			*/
		var $memberOnly;
		/**
			* Whether this widget is marked as a standard Jadu widget
			* @var integer
			* @access public
			*/
		var $standard;
		/**
			* The ID of this {@link Administrator} that created this widget
			* @var integer
			* @access public
			*/
		var $adminID;
		/**
			* The date this widget was created as a UNIX timestamp
			* @var integer
			* @access public
			*/
		var $dateCreated;
		/**
			* Whether or not this widget is live [0 - Not live], [1 - Live]
			* @var integer
			* @access public
			*/
		var $live;
		/**
			* Whether or not this widget is from the mainSite [0 - from galaxy] [1 - from main site]
			* @var integer
			* @access public
			*/
		var $mainSite;
		/**
			* The database connection for the homepage widget
			* @var object
			* @access public
			*/
		var $_db;
		/**
			* The scope in which the widget can be used [0 - all sites] [1 - this site only] [2 - galaxy only]
			* @var integer
			* @access public
			*/
		var $scope;
	
		/**
			* The constructor for the HomepageWidget class sets default values.
			*/
		function HomepageWidget()
		{
			$this->id = -1;
			$this->internalID = '';
			$this->title = '';
			$this->defaultWidthPercentage = '';
			$this->defaultBackgroundColour = '';
			$this->description = '';
			$this->previewImage = '';
			$this->contentCodeBehind = '';
			$this->contentCode = '';
			$this->contentJs = '';
			$this->settingsCode = '';
			$this->jsCode = '';
			$this->requiresCategory = 0;
			$this->minimumWidth = 0;
			$this->limitUse = 0;
			$this->memberOnly = 0;
			$this->standard = 0;
			$this->adminID = -1;
			$this->dateCreated = '';
			$this->live = 0;
			$this->mainSite = -1;
			$this->scope = -1;
		}
		
		/**
		 * Create a new homepage widget record.
		 *
		 * @return integer The ID number of the newly inserted HomepageWidget record.
		 */
		function insert ()
		{
			$db = Jadu_Service_Container::getInstance()->getSiteDB();
			$admin = Jadu_Service_Container::getInstance()->getJaduAdministrator()->getCurrentAdmin();

			$query = "INSERT INTO " . HOMEPAGE_WIDGETS_TABLE . " (title, defaultWidthPercentage, defaultBackgroundColour,
			         description, previewImage, requiresCategory, minimumWidth, 
				     limitUse, memberOnly, standard, adminID, dateCreated, live, scope) VALUES (" . 
			$db->qstr($this->title) . ", " .
			(int) $this->defaultWidthPercentage . ", " .
			$db->qstr($this->defaultBackgroundColour) . ", " .
			$db->qstr($this->description) . ", " .
			$db->qstr($this->previewImage) . ", " .
			(int) $this->requiresCategory . ", " .
			(int) $this->minimumWidth . ", " .
			(int) $this->limitUse . ", " .
			(int) $this->memberOnly . ", " .
			(int) $this->standard . ", " .
			(int) $this->adminID . ", " .
			$db->DBTimeStamp(time()) . ", " .
			(int) $this->live . ", " .
			(int) $this->scope . ")";
		
			$db->Execute($query);
		
			$this->id = $db->Insert_ID();
			$this->publishCode();
		
			deleteTableCache(HOMEPAGE_WIDGETS_TABLE);

			Jadu_Service_Container::getInstance()->getEventContainer()->fire('widget.save', (new Jadu_Page_Widget_Event_Object($this, $admin, true)));					
		
			return $this->id;
		}
		
		/**
		 * Update a homepage widget record.
		 *
		 */
		function update ()
		{
			$db = Jadu_Service_Container::getInstance()->getSiteDB();
			$admin = Jadu_Service_Container::getInstance()->getJaduAdministrator()->getCurrentAdmin();

			$query = "UPDATE " . HOMEPAGE_WIDGETS_TABLE . " SET " .
					 "title = " . $db->qstr($this->title) . ", " .
					 "defaultWidthPercentage = " . (int) $this->defaultWidthPercentage . ", " .
					 "defaultBackgroundColour = " . $db->qstr($this->defaultBackgroundColour) . ", " .
					 "description = " . $db->qstr($this->description) . ", " .
					 "previewImage = " . $db->qstr($this->previewImage) . ", " .
					 "requiresCategory = " . (int) $this->requiresCategory . ", " .
					 "minimumWidth = " . (int) $this->minimumWidth . ", " . 
					 "limitUse = " . (int) $this->limitUse . ", " .
					 "memberOnly = " . (int) $this->memberOnly . ", " .
					 "standard = " . (int) $this->standard . ", " .
					 "adminID = " . (int) $this->adminID . ", " .
					 "live = " . (int) $this->live . ", " .
					 "scope = " . (int) $this->scope . " " .
					 "WHERE id = " . (int) $this->id;
			
			$db->Execute($query);
			$this->publishCode();

			Jadu_Service_Container::getInstance()->getEventContainer()->fire('widget.save', (new Jadu_Page_Widget_Event_Object($this, $admin)));					
						
			deleteTableCache(HOMEPAGE_WIDGETS_TABLE);
		}

		function canBeDeletedOnSite()
		{
			if (INSTALLATION_TYPE == GALAXY) {
				if ($this->mainSite == 1) {
					return false;
				}
				return true;
			}
			else {
				if ($this->standard == 1) {
					return false;
				}
				return true;
			}
		}
		
	
		/**
		 * Delete a homepage widget record.
		 */
		function delete()
		{
			$db = Jadu_Service_Container::getInstance()->getSiteDB();
			$admin = Jadu_Service_Container::getInstance()->getJaduAdministrator()->getCurrentAdmin();

			$query = "DELETE FROM " . HOMEPAGE_WIDGETS_TABLE . " WHERE id = " . (int) $this->id;
	
			$db->Execute($query);
	
			if ($db->Affected_Rows() == 1) {
				removeDirectory(HOME_DIR . 'var/widgets/' . $this->id);
					
				if (class_exists('Jadu_ClusterSync')) {
					Jadu_ClusterSync::deleteDirectory(HOME_DIR . 'var/widgets/' . $this->id);
				}

				Jadu_Service_Container::getInstance()->getEventContainer()->fire('widget.delete', (new Jadu_Page_Widget_Event_Object($this, $admin)));					
				
				deleteTableCache(HOMEPAGE_WIDGETS_TABLE);
			}
		}
		
		
		/**
		 * Duplicate a homepage widget record.
		 *
		 * @param integer $id The id of the homepage widget record to duplicate.
		 */
		function duplicate()
		{
			$homepageWidget = getHomepageWidget($this->internalID);
		
			$homepageWidget->title .= ' (duplicate)';
			$homepageWidget->standard = 0;
			$homepageWidget->setMainSite();
			
			if ($homepageWidget->id == -1) {
				return -1;
			}
		
			$duplicatedHomepageWidgetID = $homepageWidget->insert();
			return $duplicatedHomepageWidgetID;
		}
		
		/**
		 * Returns the code from the given file if it exists, otherwise the default
		 * value is returned.
		 *
		 * @param string $file
		 * @param string $default
		 * @return string
		 */
		function getCode($file, $default = '')
		{
			$widgetBaseFolder = $this->mainSite ? MAIN_HOME_DIR : HOME_DIR;

			$path = $widgetBaseFolder . 'var/widgets/' . $this->id . '/' . $file;			

			if (is_readable($path)) {
				return file_get_contents($path);
			}
				
			return $default;
		}
		
		/**
		 * Returns the internalID of the widget.  This helps to differentiate where the widget originates.  The mainSite or from the galaxy.
		 *
		 * @return string
		 */	
		
		function getInternalID()
		{
			return rtrim(strtr(base64_encode($this->id . $this->mainSite), '+/', '-_'), '='); 
		}
		
		/**
		 * Populate the HomepageWidget object with the data retrieved from the database
		 *
		 * @param array $row
		 */
		function populate(array $row)
		{
			$mainDB = Jadu_Service_Container::getInstance()->getMainDB();
				
			$this->id = (int) $row['id'];
			$this->title = $row['title'];
			$this->defaultWidthPercentage = $row['defaultWidthPercentage'];
			$this->defaultBackgroundColour = $row['defaultBackgroundColour'];
			$this->description = $row['description'];
			$this->previewImage = $row['previewImage'];
			$this->requiresCategory = $row['requiresCategory'];
			$this->minimumWidth = $row['minimumWidth'];
			$this->limitUse = $row['limitUse'];
			$this->memberOnly = (int) $row['memberOnly'];
			$this->standard = (bool) $row['standard'];
			$this->adminID = (int) $row['adminID'];
			$this->dateCreated = $mainDB->UnixTimeStamp($row['dateCreated']);
			$this->live = (bool) $row['live'];
			$this->scope = (int) $row['scope'];
			$this->internalID = $this->getInternalID();

			if (SCRIPTING_LANGUAGE == DOT_NET) {
				$this->contentCode = $this->getCode('public.ascx', $row['contentCode']);
				$this->contentCodeBehind = $this->getCode('public.ascx.cs', $row['contentCodeBehind']);
			}
			else {
				$this->contentCode = $this->getCode('public.php', $row['contentCode']);
			}
				
			$this->contentJs = $this->getCode('public.js', $row['contentJs']);
			$this->settingsCode = $this->getCode('secure.php', $row['settingsCode']);
			$this->jsCode = $this->getCode('secure.js', $row['jsCode']);
		}
		
		/**
		 * Publishes the homepage widget code to files
		 *
		 */
		public function publishCode()
		{	
			$widgetBasePath = HOME_DIR . 'var/widgets/';
			
			if (!is_dir($widgetBasePath)) {
				mkdir($widgetBasePath);
			}
			
			$path = $widgetBasePath . $this->id . '/';
				
			if (!is_dir($path)) {
				mkdir($path);
			}
				
			if (SCRIPTING_LANGUAGE == DOT_NET) {
				$this->updateWidgetCodeFile($path . 'public.ascx', $this->contentCode);
				$this->updateWidgetCodeFile($path . 'public.ascx.cs', $this->contentCodeBehind);
			}
	
			$this->updateWidgetCodeFile($path . 'public.php', $this->contentCode);
			$this->updateWidgetCodeFile($path . 'public.js', $this->contentJs);
			$this->updateWidgetCodeFile($path . 'secure.php', $this->settingsCode);
			$this->updateWidgetCodeFile($path . 'secure.js', $this->jsCode);
	
			$this->removePreviousWidgetPlaceHolderFiles($path);
			
			// remove all widget js files
			$jsFiles = getFullPathsToAllWidgetJavascriptFiles();
			foreach ($jsFiles as $file) {
				if (file_exists($file)) {
					unlink($file);
				}
			}
			
			foreach ($jsFiles as $file) {
				if (class_exists('Jadu_ClusterSync')) {
					Jadu_ClusterSync::delete($file);
				}
			}
			
			file_put_contents($path . 'WIDGET_' . $this->title, '', LOCK_EX);
				
			if (class_exists('Jadu_ClusterSync')) {
				Jadu_ClusterSync::writeDirectory($path);
			}
		}
		
		/*
		 * Remove all previous widget place holder files
		 * 
		 * @param string $path - The path to the widget folder
		 *  
		 */
		
		public function removePreviousWidgetPlaceHolderFiles($path)
		{
			$files = glob($path . 'WIDGET_*');
			foreach ($files as $file) {
				if (is_file($file) && is_writable($file)) {
					unlink($file);
					if (class_exists('Jadu_ClusterSync')) {
						Jadu_ClusterSync::delete($file);
					}
				}
			}

			// remove files which were created the old way
			$files = glob($path . 'WIDGETS_*');
			foreach ((array)$files as $file) {
				if (is_file($file) && is_writable($file)) {
					unlink($file);
					if (class_exists('Jadu_ClusterSync')) {
						Jadu_ClusterSync::delete($file);
					}
				}
			}
		}
		
		/**
		 * set the internalID of the widget.  This helps to differentiate where the widget originates.
		 *
		 * @return string
		 */	
		
		function setInternalID($internalID)
		{
			$this->internalID = $internalID;
			
			$this->decodeInternalID();
		}
		
		function decodeInternalID()
		{
			$internalID = $this->internalID;

			$decodedInternalID = base64_decode($internalID);
			
			$this->id = substr($decodedInternalID, 0, -1);
			$this->mainSite = substr($decodedInternalID, -1);
			
			$this->setDBConnection();
		}
		
		function setDBConnection()
		{
			if ($this->mainSite) {
				global $mainDB;
				$this->_db = $mainDB;
			}
			else {
				global $db;
				$this->_db = $db;			
			}
		}	
		
		function setMainSite()
		{
			$this->mainSite = (int)(INSTALLATION_TYPE != GALAXY);
		}
		
		public function updateWidgetCodeFile($pathToWidgetCode, $widgetCode) {
			if ($widgetCode == '') {
				if (file_exists($pathToWidgetCode)) {
					unlink($pathToWidgetCode);
				}
			}
			else {
				file_put_contents($pathToWidgetCode, $widgetCode, LOCK_EX);
			}
		}
		
		function isVisible ()
		{			
			$visible = true;
			if (defined('HOMEPAGES_INVISIBLE_WIDGET_IDS')) {
				$invisibleIDs = explode(',', HOMEPAGES_INVISIBLE_WIDGET_IDS);
				if (in_array($this->getInternalID(), $invisibleIDs)) {
					$visible = false;
				}
			}
			
			return $visible;
		}
		
		/**
		* Sets visibility of Widget to determine whether or not it can be used
		* in the Homepages designer.
		* @param boolean $visible Set visibility to true or false.
		*/
		function setVisibility ($visible)
		{
			$invisibleIDs = array();
			if (defined('HOMEPAGES_INVISIBLE_WIDGET_IDS')) {
				$invisibleIDs = explode(',', (string)HOMEPAGES_INVISIBLE_WIDGET_IDS);
			}
			
			$isCurrentlyVisible = !in_array($this->getInternalID(), $invisibleIDs);
			
			if (!$visible && $isCurrentlyVisible) {
				$invisibleIDs[] = $this->getInternalID();
			}
			else if ($visible && !$isCurrentlyVisible) {
				unset($invisibleIDs[array_search($this->getInternalID(), $invisibleIDs)]);
			}
			
			if (!defined('HOMEPAGES_INVISIBLE_WIDGET_IDS')) {
				$constant = new Constant();
				$constant->name = 'HOMEPAGES_INVISIBLE_WIDGET_IDS';
				$constant->type = 'string';
				$constant->editable = '1';
				addConstant($constant);
			}
			
			setConstant('HOMEPAGES_INVISIBLE_WIDGET_IDS', implode(',', $invisibleIDs));
		}
		
	}
	
	/**
	* Builds a SELECT query for JaduHomepageWidgets with no WHERE clause
	* @return string The query
	*/
	function getHomepageWidgetsSelectQuery()
	{
		$mainDB = Jadu_Service_Container::getInstance()->getMainDB();
	
		$dateFunctions['dateCreated'] = $mainDB->SQLDate("Y-m-d", "dateCreated");
		
		$query = "SELECT id, title, defaultWidthPercentage, defaultBackgroundColour, description,
				 previewImage, contentCode, contentCodeBehind, contentJs, settingsCode, jsCode, requiresCategory, 
				 minimumWidth, limitUse, memberOnly, standard,
				 adminID, " . $dateFunctions['dateCreated'] . " AS dateCreated, live, scope
				 FROM " . HOMEPAGE_WIDGETS_TABLE . ' ';
		return $query;
	}

	/**
	 * Retrieve a homepage widget record from the database.
	 *
	 * @param string $internalID The internalID for the homepage widget.
	 * @return HomepageWidget Returns an instance of a homepage object.
	 */
	function getHomepageWidget ($internalID)
	{
		$homepage = new HomepageWidget();
		$homepage->setInternalID($internalID);	
		
		$query  = getHomepageWidgetsSelectQuery();
		$query .= "WHERE id = " . (int) $homepage->id;

		if ($homepage->mainSite) {
			return getHomepageWidgetFromMainSite($query);		
		}
		else {
			return getHomepageWidgetFromSite($query);		
		}
	}
	
	/**
	 * Retrieve a homepage widget record from the database.
	 *
	 * @param string $query The query used to get a homepage widget from the main site.
	 * @return HomepageWidget Returns an instance of a homepage widget object.
	 */
	function getHomepageWidgetFromMainSite ($query)
	{
		$mainDB = Jadu_Service_Container::getInstance()->getMainDB();
		
		$cache = new Cache(HOMEPAGE_WIDGETS_TABLE, $query, getMainDomain());
	
		if ($cache->isEmpty()) {
			$result = $mainDB->Execute($query);
				
			$widget = new HomepageWidget();
			$widget->mainSite = 1;						
			if ($result && !$result->EOF) {
				$widget->populate($result->fields);

			}
			$cache->setData($widget);
			return $widget;
		}
		else {
			return $cache->data;
		}
	}
	
	/**
	 * Retrieve a homepage widget record from the database.
	 *
	 * @param string $query The query used to get a homepage widget from the site.
	 * @return HomepageWidget Returns an instance of a homepage widget object.
	 */
	function getHomepageWidgetFromSite ($query)
	{
		$db = Jadu_Service_Container::getInstance()->getSiteDB();
		
		$cache = new Cache(HOMEPAGE_WIDGETS_TABLE, $query);
	
		if ($cache->isEmpty()) {
			$result = $db->Execute($query);
				
			$widget = new HomepageWidget();
			if ($result && !$result->EOF) {
				$widget->mainSite = 0;
				$widget->populate($result->fields);
			}			
			$cache->setData($widget);
			return $widget;
		}
		else {
			return $cache->data;
		}
	}
	
	/**
	* Gets an array of all HomepageWidget records that can be viewed on the site
	* with the GUID given.
	* For main CMS: standard, enterprise and local widgets
	* For galaxy sites: standard and local widgets, plus enterprise widgets if 
	* HOMEPAGES_SHOW_ALL_WIDGETS constant is true.
	* @param array[integer]HomepageWidget Homepage Widgets indexed by widget ID.
	*/	
	function getAllHomepageWidgetsForSite ()
	{
		if (INSTALLATION_TYPE == GALAXY) {
			return getAllHomepageWidgetsForGalaxy();			
		}
		return getAllHomepageWidgets();
	}
	
	function getAllHomepageWidgetsForGalaxy ()
	{
		$widgetsForSite = array();
		$allEnterpriseWidgets = array();
		$allGalaxyHomepageWidgets = array();
		
		$allMainSiteWidgetsAvailableToGalaxy = getAllMainSiteWidgetsAvailableToGalaxy();
			
		$allGalaxyHomepageWidgets = getAllGalaxyHomepageWidgets();			
		
		foreach ($allMainSiteWidgetsAvailableToGalaxy as $widget) {
			$widgetsForSite[$widget->getInternalID()] = $widget;			
		}
		
		foreach ($allGalaxyHomepageWidgets as $widget) {
			$widgetsForSite[$widget->getInternalID()] = $widget;
		}

		usort($widgetsForSite, 'orderByTitle');		
		return buildArrayUsingInternalIDasKey($widgetsForSite); 
	}
	
	/*
	 * A function to build an array of widget objects using the internalID as the key
	 * 
	 * @param array[] HomepageWidget
 	 * @return array[]HomepageWidget Returns an array of instances of HomepageWidget.  The array is keyed by the homepage widget internalID 
	 */
	
	function buildArrayUsingInternalIDasKey($widgets) 
	{
		$sorted = array();
		if (is_array($widgets)) {
			foreach($widgets as $widget) {
				$sorted[$widget->getInternalID()] = $widget;
			}
		}
		return $sorted;
	}
	
	
	/**
	 * A function to retrieve all homepage widget records.
	 *
	 * @param string $orderby The field to order the results by.
	 * @return array[]HomepageWidget Returns an array of instances of HomepageWidget.
	 */
	function getAllMainSiteWidgetsAvailableToGalaxy($orderby = 'title')
	{
		$mainDB = Jadu_Service_Container::getInstance()->getMainDB();
	
		$dateFunctions['dateCreated'] = $mainDB->SQLDate("Y-m-d", "dateCreated");

		if (defined('HOMEPAGES_SHOW_ALL_WIDGETS') && HOMEPAGES_SHOW_ALL_WIDGETS == false) {
			$widgetScope = '(scope = 0 OR scope = 2)';
		}
		else {
			$widgetScope = '(scope = 0 OR scope = 2 OR scope = 3)';	
		}
	
		$query = "SELECT id, title, defaultWidthPercentage, defaultBackgroundColour, description,
				 previewImage, contentCode, contentCodeBehind, contentJs, settingsCode, jsCode, requiresCategory, 
				 minimumWidth, limitUse, memberOnly, standard,
				 adminID, " . $dateFunctions['dateCreated'] . " AS dateCreated, live, scope 
			     FROM " . HOMEPAGE_WIDGETS_TABLE . "
			     WHERE $widgetScope
			     ORDER BY $orderby";

		$cache = new Cache(HOMEPAGE_WIDGETS_TABLE, $query, getMainDomain());
	
		if ($cache->isEmpty()) {
	
			$allHomepageWidgets = array();
	
			$result = $mainDB->Execute($query);
	
			if ($result) {
				while (!$result->EOF) {
					$homepage = new HomepageWidget();
					$homepage->mainSite = 1;								
					$homepage->populate($result->fields);
	
					$allHomepageWidgets[$homepage->id] = $homepage;
		
					$result->MoveNext();
				}
			}
	
			$cache->setData($allHomepageWidgets);
			return $allHomepageWidgets;
		}
		else {
			return $cache->data;
		}
	}
	
	/**
	 * A function to retrieve all homepage widget records.
	 *
	 * @param string $orderby The field to order the results by.
	 * @return array[]HomepageWidget Returns an array of instances of HomepageWidget.
	 */
	function getAllGalaxyHomepageWidgets($orderby = 'title')
	{
		$db = Jadu_Service_Container::getInstance()->getSiteDB();
		
		$query  = getHomepageWidgetsSelectQuery();
		$query .= "ORDER BY " . $orderby;
	
		$widgets = array();
	
		$cache = new Cache(HOMEPAGE_WIDGETS_TABLE, $query);
		if ($cache->isEmpty()) {
			$result = $db->Execute($query);
	
			if ($result) {
				while (!$result->EOF) {
					$homepage = new HomepageWidget();
					$homepage->mainSite = 0;										
					$homepage->populate($result->fields);

					$widgets[$homepage->id] = $homepage;
	
					$result->MoveNext();
				}
			}
			$cache->setData($widgets);
		}
		else {
			$widgets = $cache->data;
		}
		return $widgets;
	}
	
	
	/**
	 * A function to retrieve all homepage widget records from the main site.
	 *
	 * @param string $orderby The field to order the results by.
	 * @return array[]HomepageWidget Returns an array of instances of HomepageWidget.
	 */
	function getAllHomepageWidgets($orderby = 'title')
	{
		$mainDB = Jadu_Service_Container::getInstance()->getMainDB();
		
		$query  = getHomepageWidgetsSelectQuery();
		$query .= "ORDER BY $orderby";
	
		$cache = new Cache(HOMEPAGE_WIDGETS_TABLE, $query, getMainDomain());
	
		if ($cache->isEmpty()) {
	
			$allHomepageWidgets = array();
	
			$result = $mainDB->Execute($query);
	
			if ($result) {
				while (!$result->EOF) {
					$widget = new HomepageWidget();
					$widget->mainSite = 1;					
					$widget->populate($result->fields);
					
					$allHomepageWidgets[$widget->getInternalID()] = $widget;
	
					$result->MoveNext();
				}
			}
	
			$cache->setData($allHomepageWidgets);
			return $allHomepageWidgets;
		}
		else {
			return $cache->data;
		}
	}
	
	/**
	 * Return array of widgets based on the search text
	 * @param string $txt  The text to look for
	 * @return array[]HomepageWidget       The array of matches
	 */
	function getHomepageWidgetByQuery ($txt)
	{
		$mainDB = Jadu_Service_Container::getInstance()->getMainDB();
		
		$query  = getHomepageWidgetsSelectQuery();
		$query .= "WHERE title LIKE " . $mainDB->qstr('%' . $txt . '%') . " 
				  ORDER BY description";
	
		$cache = new Cache(HOMEPAGE_WIDGETS_TABLE, $query, getMainDomain());
	
		if ($cache->isEmpty()) {
			$allHomepageWidgets = array();
			$result = $mainDB->Execute($query);
			while (!$result->EOF) {
				$homepage = new HomepageWidget();
				$homepage->mainSite = 1;							
				$homepage->populate($result->fields);
				
				$allHomepageWidgets[] = $homepage;
	
				$result->MoveNext();
			}
	
			$cache->setData($allHomepageWidgets);
			return $allHomepageWidgets;
		}
		else {
			return $cache->data;
		}
	}
	
	/**
	 * Validate the details of a HomepageWidget to make sure it is suitable to add to the database.
	 *
	 * @param HomepageWidget $homepage An instance of a HomepageWidget to validate.
	 * @return array[string]boolean Returns an array of possible errors for the homepage widget.
	 */
	function validateHomepageWidget($homepage)
	{
		$errors = array();
	
		if (empty($homepage->title)) {
			$errors['title'] = true;
		}
	
		return $errors;
	}
	
	/**
	 * Gets the contentJs for all widgets and writes out a static js file.
	 */
	function publishAllWidgetsContentJs ()
	{	
		$file = getFullPathToWidgetJavascriptFile();
		
		if (!is_dir(dirname($file))) {
			mkdir(dirname($file), 0775, true);
		}
		
		if (isWriteable($file)) {
			$jsFile = fopen($file, 'w');
			if ($jsFile) {
				
				$allWidgets = getAllHomepageWidgetsForSite();
				
				foreach ($allWidgets as $widget) {
					if (trim($widget->contentJs) != '') {
						fwrite($jsFile, "/******** ".intval($widget->id)." internalID:".encodeHtml($widget->internalID)." *********/\n\n".$widget->contentJs);
					}
				}
				fclose($jsFile);
				
				if (class_exists('Jadu_ClusterSync')) {
					Jadu_ClusterSync::write($file);
				}
			}
		}
	}
	
	/**
	* Provides path to local widget.js file.
	* @return string
	*/
	function getFullPathToWidgetJavascriptFile ()
	{
		return VAR_HOME_DIR.'widgets/js/widget.js';
	}
	
	/**
	* Provides URL to local widget.js file. Creates file if
	* it doesn't exist.
	* @return string The URL to the widget.js file.
	*/
	function getURLToWidgetJavascriptFile ()
	{
		$file = getFullPathToWidgetJavascriptFile();
		
		if (file_exists($file)) {
			$modTime = filemtime($file);
		}
		else {
			publishAllWidgetsContentJs();
			$modTime = time();
		}
	
		return getStaticContentRootURL().'/widgets/js/widget.js?'.$modTime;
	}
	
	/**
	* Gets an array of file-system paths to all widget.js files for
	* both main CMS and galaxies.
	* @return array[]string
	*/
	function getFullPathsToAllWidgetJavascriptFiles ()
	{
		$allGalaxySites = getAllInstalledGalaxiesSites();
		
		$homeDir = HOME_DIR;
		if (defined('MAIN_HOME_DIR')) {
			$homeDir = MAIN_HOME_DIR;
		}
		
		$filePathTemplate = $homeDir . '%svar/widgets/js/widget.js';
			
		$paths = array();
		
		$paths[] = sprintf($filePathTemplate, '');
		
		foreach ($allGalaxySites as $galaxySite) {
			$paths[] = sprintf($filePathTemplate, 'microsites/' . $galaxySite->homeDirectory . '/');
		}
	
		return $paths;
	}
		
	/**
	 * Generates HTML from a widget using the given settings for use in the
	 * control centre (designer and track changes). If the widget requires
	 * settings and none are set, the link to "apply settings" is returned.
	 *
	 * @param integer $widgetID The ID of the HomepageWidget to generate a preview of
	 * @param array[string]string $settings An array of name mapped to value of settings for the widget
	 * @param integer $stackPosition The position of this widget in a stack (0 for non-stacked)
	 * @param Jadu_Language_Pack $lang Language pack to use when rendering preview.
     * @return string The HTML output of the widget, wrapped in divs for control centre display.	 
	 */
	function getWidgetPreview ($widgetID, $settings, $stackPosition, Jadu_Language_Pack $lang)
	{
		$widget = getHomepageWidget($widgetID);
		$widgetPreview = '';
	
		$_POST['preview'] = 'contentCode';
		$_POST['widgetID'] = $widgetID;
		$_POST['nonce'] = rand(0, 9999);
		$preview_mode = true;
	
		$sentCat = '';
		if (isset($_POST['categoryID']) && $_POST['categoryID'] != '') {
			$sentCat = $_POST['categoryID'];
		}
		$found = array();
			
		if (!empty($settings)) {

			$postSettings = array();

			foreach ($settings as $name => $value) {
				if (mb_strtolower($name) == 'img_src' && $value != '') {
					$found['img_src'] = true;
				}
				if (mb_strtolower($name) == 'content' && $value != '') {
					$found['content'] = true;
				}

				if (mb_strtolower($name) == 'img_src' && mb_strtolower(mb_substr($value,0,4)) != 'http') {
					$value = $SECURE_SERVER . '/images/' . $value;
				}
				if (mb_strtolower($name) == 'categories' && $value != '') {
					$sentCat = $value;
					$found['categories'] = true;
				}
					
				$value = str_replace("'", "\'", $value);
				$value = str_replace('_apos_', "&#39;", $value);
				$value = str_replace('_amp_', "&", $value);
				$value = str_replace('_eq_', '=', $value);
				$value = str_replace('_hash_', '#', $value);
				$value = str_replace('_ques_', '?', $value);
				$value = str_replace('_perc_', '%', $value);
					
				$widget->contentCode = str_replace('%'.mb_strtoupper($name).'%', $value, $widget->contentCode);
				$postSettings[$name] = $value;
			}

			$json = new Services_JSON();
			$_POST['settings'] = $json->encode($postSettings);
			$settings = $postSettings;
		}
			
		// Remove the ID from the multimedia placeholder so it doesn't get replaced with the player embed code
		$widget->contentCode = preg_replace('/<div id="multimedia_(\d+)_([^"]+)"/', '<img', $widget->contentCode);
			
		// Disable links
		$widget->contentCode = preg_replace('/(.*)?(<a\w+.*)(\s+\w+([^onclick]\w*=\s*(?:[\'"].*?[\'"]|[^\'">\s]+)?)+\s*|\s*)(\/?>)(.*)?/', '$1$2 onclick="return false;"$3$4$5$6$7', $widget->contentCode);

		ob_start();
		eval(' $_GET["sign_in"] = true; $_GET["categoryID"] = $sentCat; $categoryID = $sentCat; ?>'.$widget->contentCode."<?php ");
		$widgetHTML = ob_get_contents();
		ob_clean();
			
		$nonHtmlContent = trim(strip_tags(str_replace("%TITLE%", '', str_replace('%CONTENT%', '', $widgetHTML))));
		
		$widgetPreview = '<div class="styleLess">';
		
		if (!array_key_exists('img_src', $found) && 
			!array_key_exists('content', $found) &&
			!array_key_exists('categories', $found) && 
			$nonHtmlContent == '') {
				
			if ($stackPosition < 1) {
				$greedyActive = '';
			} else {
				$greedyActive = 'greedyActive = true; ';
			}
			
			if (trim($widget->settingsCode) == '' && $widget->requiresCategory == 0) {
				$widgetPreview .= '<h2>Preview not available.</h2>';
			} else {
				$widgetPreview .= '<a href="#" title="Please apply some settings to this widget." onclick="activateWidget(this.parentNode.parentNode.parentNode.parentNode); ' . $greedyActive . 'return loadLightbox(\'homepages/homepage_widget_properties\', \'lb\', \'widgetID=' . $widgetID . '&season=\' + $(\'stylesheet\').value);">Please apply some settings to this widget.</a>';
			}
		} else {
			$widgetPreview .= $widgetHTML;
		}
			
		$widgetPreview .= '</div>';

		// Fix Flash wmode parameter
		$widgetPreview = fixFlashWmode($widgetPreview);

		return $widgetPreview;
	}
	
		
	function orderByTitle($a, $b) 
	{
		return strncasecmp($a->title, $b->title, 4); 
	}
