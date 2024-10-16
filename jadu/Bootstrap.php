<?php

	/**
	 * The bootstrap class
	 *
	 * @package Bootstrap
	 * @copyright All Contents (c) 2010 Jadu Ltd.
	 * @author Jadu Ltd.
	 */

	require_once('Config.php');
	require_once('Config/Manager.php');
	require_once('DataStore.php');
	require_once('DataStore/Database.php');
	require_once('JaduADODB.php');
	require_once('JaduSiteRegistry.php');
	require_once('JaduConstantsFunctions.php');

	// Type of OS (possible values of PLATFORM)
	define('LINUX', 0);
	define('WINDOWS', 1);

	// Type of site (possible values of INSTALLATION_TYPE)
	define('INTRANET', 0);
	define('INTERNET', 1);
	define('GALAXY', 2);

	// Type of server software (possible values of SERVER_SOFTWARE)
	define('APACHE', 0);
	define('IIS', 1);

	// Type of scripting language (possible values of SCRIPTING_LANGUAGE)
	define('PHP', 0);
	define('DOT_NET', 1);

	/**
	 * The bootstrap class
	 *
	 * @package Bootstrap
	 */
	class Jadu_Bootstrap
	{
		protected $db;
		protected $site;
		protected $configManager;
		protected $constants = array();
		protected $server;

		/**
		 * Constructor
		 *
		 * @param array $server The SERVER super-global values
		 */
		public function __construct(array $server)
		{
			$this->server = $server;
		}

		/**
		 * Set the config manager used to load configuration files
		 *
		 * @param Jadu_Config_Manager $configManager
		 * @return void
		 */
		public function setConfigManager(Jadu_Config_Manager $configManager)
		{
			$this->configManager = $configManager;

			// Set the singleton instance so it can be used globally
			Jadu_Config_Manager::setInstance($this->configManager);
		}

		/**
		 * Set the main database connection
		 *
		 * @param ADOConnection $db
		 * @return void
		 */
		public function setMainDb(ADOConnection $db)
		{
			$this->db = $db;

			// Backwards compatibility fix
			$GLOBALS['mainDB'] = $this->db;
		}

		/**
		 * Get the main database connection if set, otherwise create a new
		 * connection using dbConnect().
		 *
		 * @return ADOConnection
		 */
		public function getMainDb()
		{
			if ($this->db === null) {
				$this->setMainDb(dbConnect());
			}

			return $this->db;
		}

		/**
		 * Returns a database connection for the requested site
		 *
		 * @todo Refactor out dbConnect as it is not testable
		 * @param JaduDataSite $site
		 * @return ADOConnection
		 */
		public function getSiteDb($site)
		{
			$db = $this->getMainDb();

			// If the main connection DB is the same as the site DB then re-use the connection
			if ($site->dbName == '' || $site->dbName == $db->database) {
				return $db;
			}

			// Create a new connection
			return dbConnect($site);
		}

		/**
		 * Set the requested site
		 *
		 * @param JaduDataSite $site
		 */
		public function setSite(JaduDataSite $site)
		{
			$this->site = $site;
		}

		/**
		 * Returns the requested site
		 *
		 * @return JaduDataSite
		 */
		public function getSite()
		{
			if ($this->site === null) {
				// Initialise the main DB connection
				$db = $this->getMainDb();

				if (isset($this->server['HTTP_HOST'])) {
					$site = getLongestJaduSiteWithURL($this->server['HTTP_HOST'].$this->getRequestUri());
				}

				if (!isset($site) || $site->id < 1) {
					// Unknown domain, just load the main CMS by database name
					$site = getFirstJaduSiteWithDBName($db->database, true, false);
					if ($site->id < 1) {
						if (isset($this->server['HTTP_HOST']) && $this->server['HTTP_HOST'] != '') {
							// Site doesn't exist, add a new entry for the current domain
							$site = addMainCMSSite();
						}
						else {
							$site = new JaduDataSite();
							$site->preferredConnectionType = DB_DBMS;
							$site->dbUsername = DB_USERNAME;
							$site->dbPassword = DB_PASSWORD;
							$site->dbName = DB_NAME;
							$site->isMainSite = 1;
							$site->isControlCentre = 1;
							if (DB_USE_DSN) {
								$site->dbDSN = DB_DSN;
							}
						}
					}
				}

				$this->site = $site;
			}

			return $this->site;
		}

		/**
		 * Get the REQUEST_URI server parameter
		 *
		 * Due to an issue with IIS the REQUEST_URI server global isn't always
		 * available so we fallback to using PHP_SELF + QUERY_STRING instead.
		 *
		 * @return string
		 */
		public function getRequestUri()
		{
			if (!isset($this->server['REQUEST_URI']) && isset($this->server['PHP_SELF'])) {
				$this->server['REQUEST_URI'] = substr($this->server['PHP_SELF'], 0);
				if (isset($this->server['QUERY_STRING']) && $this->server['QUERY_STRING'] != '') {
					$this->server['REQUEST_URI'] .= '?' . $this->server['QUERY_STRING'];
				}

				// Fix for the rest of the CMS where $_SERVER is accessed directly
				$_SERVER['REQUEST_URI'] = $this->server['REQUEST_URI'];
			}

			if (isset($this->server['REQUEST_URI'])) {
				return $this->server['REQUEST_URI'];
			}
		}

		/**
		 * Mark the constant and value to be defined.
		 *
		 * If the constant is already defined within PHP then the constant value
		 * will be used instead of the value passed as an argument to the method.
		 *
		 * This is so that the global variable is still set with the same value
		 * as the constant if the constant is defined elsewhere, such as in the
		 * database table JaduConstants.
		 *
		 * @param string $name
		 * @param string $value
		 * @return void
		 */
		public function define($name, $value, $global = false)
		{
			if (!$this->defined($name)) {
				$this->constants[$name] = defined($name) ? constant($name) : $value;
			}
		}

		/**
		 * Check whether the constant is defined or marked to be defined
		 *
		 * @param string $name
		 * @return boolean
		 */
		public function defined($name)
		{
			return defined($name) || isset($this->constants[$name]);
		}

		/**
		 * Get the constant value or marked to be defined constant value
		 *
		 * @param string $name
         * @param string $default
         * @return mixed
		 */
		public function constant($name, $default = null)
		{
			if (defined($name)) {
				return constant($name);
			}

			if (isset($this->constants[$name])) {
				return $this->constants[$name];
			}

			return $default;
		}

		/**
		 * Get the constants to be defined
		 *
		 * @return array
		 */
		public function getConstants()
		{
			return $this->constants;
		}

		/**
		 * Initialise the CMS
		 *
		 * @return void
		 */
		public function initialise()
		{
			// Define the system constants
			$config = $this->configManager->getConfig('system');
			if ($config !== null) {
				foreach ($config->toArray() as $name => $value) {
					$name = strtoupper($name);
					if (!defined($name)) {
						define($name, $value);
					}
				}
			}

			Jadu_DataStore::init(CACHE_DATA_STORE, MAIN_HOME_DIR . 'config/datastore.xml');

			// Determine which site (main or galaxy) has been accessed
			$site = $this->getSite();
			$GLOBALS['site'] = $site;

			// Get the database connection for the current site
			$db = $this->getSiteDb($site);
			$GLOBALS['db'] = $db;

			// Define database constants
			$constants = getAllConstants();
			if (is_array($constants)) {
				foreach ($constants as $constant) {
					if (!defined($constant->name)) {
						switch ($constant->type) {
							case 'bool':
								$value = $constant->value == 'true';
								break;

							case 'int':
								$value = (int) $constant->value;
								break;

							case 'float':
								$value = (float) $constant->value;
								break;

							case 'secure':
							case 'string':
							default:
								$value = $constant->value;
						}

						define($constant->name, $value);
					}
				}
			}

			// Define constants
			$this->initEnvironmentConstants($site);

			$redirectUrl = $this->initDomainConstants($site);
			if ($redirectUrl !== null) {
				// Redirect if necessary
				header('Location: ' . $redirectUrl);
				exit;
			}

			$this->initPathConstants($site);

			foreach ($this->getConstants() as $name => $value) {
				if (!defined($name)) {
					define($name, $value);
				}

				// Backwards compatibility fix
				$GLOBALS[$name] = $value;
			}

			if (ini_get('date.timezone') == '') {
				// Scheduled tasks compatibility
				ini_set('date.timezone', 'Europe/London');
				if (defined('DEFAULT_TIMEZONE')) {
					ini_set('date.timezone', DEFAULT_TIMEZONE);
				}
			}

			// Locale
			if (defined('LOCALE')) {
				setlocale(LC_TIME, LOCALE);
			}

			// Define configuration constants/globals
			$config = $this->configManager->getConfig('constants');
			if ($config !== null) {
				foreach ($config->toArray() as $name => $value) {
					$name = strtoupper($name);
					if (!defined($name)) {
						define($name, $value);
					}
				}
			}

			// Set global variables
			$config = $this->configManager->getConfig('globals');
			if ($config !== null) {
				foreach ($config->toArray() as $name => $value) {
					$GLOBALS[strtoupper($name)] = $value;
				}
			}

		/**	$this->initClusterSync(); **/
			$this->initClusterCache();
			$this->initDatabaseDataStore($db);
			$this->initHelperFunctions();
		}

		/**
		 * Initialise the environment constants for the given site
		 *
		 * @param JaduDataSite $site
		 * @return void
		 */
		public function initEnvironmentConstants(JaduDataSite $site)
		{
			if ($site->isMainSite) {
				$this->define('INSTALLATION_TYPE', INTERNET);
			}
			else {
				$this->define('INSTALLATION_TYPE', GALAXY);
			}

			if (!isset($this->server['SCRIPT_FILENAME']) || substr($this->server['SCRIPT_FILENAME'], 0, 1) == '/') {
				$this->define('PLATFORM', LINUX);
			}
			else {
				$this->define('PLATFORM', WINDOWS);
			}

			// Grab the constant and don't rely on the $_SERVER['SERVER_SOFTWARE'] but maintain BC at the same time.
			$serverSoftware = $this->constant('SERVER_SOFTWARE', (isset($this->server['SERVER_SOFTWARE']) ? $this->server['SERVER_SOFTWARE'] : null));

			// Make sure serverSoftware has been set
			if (!is_null($serverSoftware)) {
				if (strpos(strtolower($serverSoftware), 'apache') !== false) {
					$this->define('SERVER_SOFTWARE', APACHE);
				}
				else {
					$this->define('SERVER_SOFTWARE', IIS);
				}
			}

			$this->define('NEWLINE', PHP_EOL);
		}

		/**
		 * Initialise the domain constants for the given site.
		 *
		 * If the return value is not null then it is the URL to redirect to.
		 *
		 * @param JaduDataSite $site
		 * @return string
		 */
		public function initDomainConstants(JaduDataSite $site)
		{
			$inControlCentre = preg_match('#^/?([a-z0-9]+/)?jadu/?#i', $this->getRequestUri());

			// If the site we've found is marked as control centre and we're viewing the control centre
			if ($site->isControlCentre && $inControlCentre) {
				$this->define('CC_DOMAIN', $site->siteUrl);
			}

			if (!$site->isMainSite) {
				// Determine the correct galaxy shortcut
				include_once('galaxies/JaduGalaxiesSites.php');
				include_once('galaxies/JaduGalaxiesURL.php');
				include_once('galaxies/JaduGalaxiesUser.php');

				$galaxySite = getGalaxiesSiteByDatabaseName($site->dbName);
				$galaxyURLs = $galaxySite->urls;

				// Try and determine shortcut based on URL
				$galaxyShortcut = substr($site->siteUrl, strpos($site->siteUrl, "/") + 1);
				foreach ($galaxyURLs as $galaxyURL) {
					if (!$galaxyURL->registeredDomain && $galaxyURL->url == $galaxyShortcut) {
						$this->define('GALAXY_SHORTCUT', $galaxyURL->url);
						break;
					}
				}
				// shortcut from URL doesn't match the galaxies urls - most likely accessed via domain
				if (!$this->defined('GALAXY_SHORTCUT')) {
					foreach ($galaxyURLs as $galaxyURL) {
						if (!$galaxyURL->registeredDomain) {
							$this->define('GALAXY_SHORTCUT', $galaxyURL->url);
							break;
						}
					}
				}

				$preferredUrl = getGalaxiesURLDefault($galaxySite->id);
				if ($preferredUrl->id > 0) {
					if ($preferredUrl->registeredDomain == 1) {
						$this->define('DOMAIN', $preferredUrl->url.'/'.$this->constant('GALAXY_SHORTCUT'));
					}
					else {
						$this->define('DOMAIN', $this->constant('MAIN_DOMAIN').'/'.$preferredUrl->url);
					}
				}
				else {
					// Attempt to maintain the host from the current request
					if (isset($this->server['HTTP_HOST']) && $this->server['HTTP_HOST'] != '' &&
						$this->server['HTTP_HOST'] != $this->constant('MAIN_DOMAIN')) {

						// Validate the host is a registered domain
						foreach ($galaxyURLs as $galaxyURL) {
							if ($galaxyURL->registeredDomain == 1 && $galaxyURL->url == $this->server['HTTP_HOST']) {
								$this->define('DOMAIN', $this->server['HTTP_HOST'].'/'.$this->constant('GALAXY_SHORTCUT'));
								break;
							}
						}
					}

					if (!$this->defined('DOMAIN')) {
						$this->define('DOMAIN', $this->constant('MAIN_DOMAIN').'/'.$this->constant('GALAXY_SHORTCUT'));
					}
				}

				if (!$this->defined('CC_DOMAIN')) {
					$ccSites = getJaduSitesByDBName($this->getMainDb()->database, true, true);
					// We expect only one, just pick the first site
					if (count($ccSites) > 0) {
						$this->define('CC_DOMAIN', $ccSites[0]->siteUrl . '/' . $this->constant('GALAXY_SHORTCUT'));
					}
				}
			}

			if (!$this->defined('DOMAIN') || !$this->defined('CC_DOMAIN')) {
				// Loop through all the sites for this database
				$dbSites = getJaduSitesByDBName($site->dbName, true, false);
				foreach ($dbSites as $dbSite) {
					if ($this->defined('DOMAIN') && $this->defined('CC_DOMAIN')) {
						break;
					}

					if ($dbSite->isControlCentre && !$this->defined('CC_DOMAIN')) {
						$this->define('CC_DOMAIN', $dbSite->siteUrl);
					}

					if (!$dbSite->isControlCentre && !$this->defined('DOMAIN')) {
						$this->define('DOMAIN', $dbSite->siteUrl);
					}
				}
			}

			// Catch any cases missed by loop above
			if (!$this->defined('CC_DOMAIN')) {
				$this->define('CC_DOMAIN', $site->siteUrl);
			}

			if (!$this->defined('DOMAIN')) {
				$this->define('DOMAIN', $site->siteUrl);
			}

			if (!$site->isMainSite) {
				$this->define('SECURE_DOMAIN', $this->constant('DOMAIN'));
			}

			if (!$this->defined('PROTOCOL')) {
				if (isset($this->server['HTTPS']) && !empty($this->server['HTTPS']) && $this->server['HTTPS'] != 'off') {
					$this->define('PROTOCOL', 'https://');
				}
				else {
					$this->define('PROTOCOL', 'http://');
				}
			}

			if (strpos($this->getRequestUri(), '/jadu') === 0 && !$site->isControlCentre) {
				$relatedSites = getJaduSitesByDBName($site->dbName, true, false);
				foreach ($relatedSites as $relatedSite) {
					if ($relatedSite->isControlCentre == 1) {
						// TODO: Remove the header() and exit() calls to make it testable
						return $this->constant('PROTOCOL') . $relatedSite->siteUrl . '/jadu';
					}
				}
			}

			$this->define('STYLES_DIRECTORY', $this->constant('PROTOCOL') . $this->constant('DOMAIN') . '/site/styles/');
			$this->define('SECURE_SERVER', $this->constant('PROTOCOL') . $this->constant('CC_DOMAIN'));
			$this->define('SECURE_JADU_PATH', $this->constant('SECURE_SERVER') . '/jadu');
		}

		/**
		 * Initialise the path constants for the given site
		 *
		 * @param JaduDataSite $site
		 * @return void
		 */
		public function initPathConstants(JaduDataSite $site)
		{
			if ($site->isMainSite) {
				$this->define('HOME_DIR', $this->constant('MAIN_HOME_DIR'));
			}
			else {
				$this->define('HOME_DIR', $this->constant('MAIN_HOME_DIR') . 'microsites/' . $site->dbName . '/');
			}

			$this->define('VAR_HOME_DIR', $this->constant('HOME_DIR') . 'var/');
			$this->define('MAIN_VAR_HOME_DIR', $this->constant('MAIN_HOME_DIR') . 'var/');

			$this->define('VAR_TMP_DIR', $this->constant('VAR_HOME_DIR') . 'tmp/');
			$this->define('MAIN_VAR_TMP_DIR', $this->constant('MAIN_VAR_HOME_DIR') . 'tmp/');

			// The web root is dependant on the type of site
			$this->define('HOME', $this->constant('HOME_DIR') . 'public_html/');

			// The secure web root is always the web root of the main CMS
			$this->define('SECURE_HOME', $this->constant('MAIN_HOME_DIR') . 'public_html/');

			// The jadu directory is shared between the main site and galaxies
			$this->define('JADU_HOME', $this->constant('MAIN_HOME_DIR') . 'jadu/');
		}

		public function initDatabaseDataStore(ADOConnection $db)
		{
			Jadu_DataStore_Database::setDatabaseConnection($db);
		}

		/**
		 * Initialise Cluster Sync
		 *
		 * @return void
		 */
		public function initClusterSync()
		{
			if ($this->configManager->hasConfig('clustersync')) {
				require_once('ClusterSync.php');
				require_once('ClusterSync/Config.php');
				$config = $this->configManager->getConfig('clustersync', true, 'Jadu_ClusterSync_Config');

				$clusterSync = new Jadu_ClusterSync($config);
				Jadu_ClusterSync::setInstance($clusterSync);
			}
		}

		/**
		 * Initialise Cluster Cache
		 *
		 * @return void
		 */
		public function initClusterCache()
		{
			$CLUSTER_CACHE_SITES = array();
			if (file_exists(MAIN_HOME_DIR.'var/clustercachesites.ini')) {
				$config = parse_ini_string(file_get_contents(MAIN_HOME_DIR.'var/clustercachesites.ini'), false);

				foreach ($config as $name => $value) {
					$parts = explode('.', $name);
					if (sizeof($parts) > 0 && $parts[0] == 'servers') {
						$cacheSite = 'http';

						if (isset($config['servers.'.$parts[1].'.secure']) && $config['servers.'.$parts[1].'.secure'] == '1') {
							$cacheSite .= 's';
						}

						if (isset($config['servers.'.$parts[1].'.ip']) &&
							$config['servers.'.$parts[1].'.ip'] != $config['serverAddress']) {

							$cacheSite .= '://'.$config['servers.'.$parts[1].'.ip'].$config['scriptPath'];
							if (!in_array($cacheSite, $CLUSTER_CACHE_SITES)) {
								$CLUSTER_CACHE_SITES[] = $cacheSite;
							}
						}
					}
				}
			}

			// Globals fix
			$GLOBALS['CLUSTER_CACHE_SITES'] = $CLUSTER_CACHE_SITES;
		}

		/**
		 * Include global helper functions
		 *
		 * @return void
		 */
		public function initHelperFunctions()
		{
			require_once(__DIR__ . '/HelperFunctions.php');
		}
	}
