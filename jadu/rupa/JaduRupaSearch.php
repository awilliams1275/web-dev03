<?php	
	require_once('JaduLibraryFunctions.php');
	require_once('rupa/JaduRupaAppliance.php');
	require_once('rupa/JaduRupaCollection.php');
	require_once('rupa/JaduRupaSearchResult.php');
	
	/**
	* JaduRupaSearch
	*
	* Handles search requests over multiple engines
	*
	* @copyright All Contents (c) 2007 Jadu Ltd.
	* @author Jadu Ltd.
	* @version 1.0
	* @package rupa
	*/
	
	
	define('FILTER_OFF', '0');
	define('FILTER_SNIPPET_ONLY', 'p');
	define('FILTER_SNIPPET_AND_DIRECTORY', '1');
	define('FILTER_DIRECTORY_ONLY', 's');
	
	/**
	* Handles search query strings for Rupa and appliances. Is also a container for search results
	* @package rupa
	*/
	class RupaSearch
	{	
		/**
		* The search term as input by the user. Related to $_GET['q']
		* @access public
		* @var string
		*/
		var $query;
		
		/**
		* Words to be excluded from the search as input by the user.
		* @access public
		* @var string
		*/
		var $excludeWords;
		
		/**
		* A list of words, any of which can be in a resulting document.
		* @access public
		* @var string
		*/
		var $orQuery;
		
		/**
		* A list of words, all of which must appear in a resulting document, in
		* the order specified.
		* @access public
		* @var string
		*/
		var $quoteQuery;
		
		/**
		* The offset to start the result set from. 
		* @access public
		* @var integer
		*/
		var $startNum;
		
		/**
		* The number of results to attempt to retrive, starting from startNum
		* @access public
		* @var integer
		*/
		var $numToShow;
		
		/**
		* Whether to sort the results by date or relevance
		* @access public
		* @var string
		*/
		var $sortBy;
		
		/**
		* Specify category IDs to restrict the search by. The IDs specified 
		* here are looked up against the local category list and used to 
		* populate a metadata filter.
		* @access public
		* @var array[]integer
		*/
		var $categoryIDs;
		
		/**
		* An array of collection names to search on. This should only be
		* populated with collection that exist as a RupaCollection.
		* @access public
		* @var array[]string
		*/
		var $sites;
		
		/**
		* The full query string, built from other query components: 
		* query + excludeWords + orWords + quoteQuery + fileFormats
		* @access public
		* @var string
		*/
		var $fullQuery;

		/**
		* The names of metadata fields to filter against
		* @access public
		* @var array[]string
		*/
		var $partialFieldsNames;
		
		/**
		* Values to look for in metadata fields
		* @access public
		* @var array[]string
		*/
		var $partialFieldsValues;
		
		/**
		* Contains the metadata fields to request from the appliance. A single 
		* entry of * indicates that all metadata fields will be retrieved.
		* @access public
		* @var array[]string
		*/
		var $metadataFieldsNames;
		
		/**
		* File formats to restrict the search by. These can be included or 
		* excluded (see $this->fileFormatInclusion). If left empty, all
		* file formats will be included.
		* @access public
		* @var array[]string
		*/
		var $fileFormats;
		
		/**
		* If set to '-' file formats specified in $this->fileFormats will all
		* be excluded from the search results, otherwise the search will 
		* be restricted to only those specified formats.
		* @access public
		* @var string
		*/
		var $fileFormatInclusion;
		
		/**
		* Indicates whether or not this search is looking at all of the available collections.
		* @access public
		* @var boolean
		*/		
		var $allSites;
		
		/**
		* Currently unused.
		* @access private
		* @var array[]string
		*/
		var $collections;
		
		var $defaultStartNum;
		
		var $defaultNumToShow;
		
		var $defaultSortBy;
		
		/**
		 * Flag specifying whether and how the results should be filtered
		 * @access public
		 * @var string
		 */
		var $filterResults;
		
		/**
		 * The default value for this->filterResults
		 *
		 */
		var $defaultFilter;

		/**
		* The appliance used for the search
		* @access public
		* @var RupaAppliance
		*/
		var $appliance;

		/**
		* Constructor, sets default values
		*/
		function RupaSearch ()
		{
			$this->appliance = null;
			$this->collectionNames = array();
			
			$this->defaultStartNum = -1;
			$this->defaultNumToShow = 10;
			$this->defaultSortBy = '';
			
			$this->query = '';
			$this->excludeWords = '';
			$this->orQuery = '';
			$this->quoteQuery = '';
			$this->startNum = $this->defaultStartNum;
			$this->numToShow = $this->defaultNumToShow;
			$this->sortBy = $this->defaultSortBy;
			$this->categoryIDs = array();
			$this->sites = array();
			$this->fullQuery = '';
			$this->partialFieldsNames = array();
			$this->partialFieldsValues = array();
			$this->metadataFieldsNames = array();
			$this->fileFormats = array();
			$this->fileFormatInclusion = '';
			$this->allSites = false;
			$this->collections = array();
			$this->filterResults = FILTER_OFF;
			$this->defaultFilter = FILTER_OFF;
			
			$this->outputFormat = 'RUPA';
			$results = array();
		}
		
		/**
		* Set up standard RupaSearch properties from GET array
		* @param array[string]mixed $get The contents of $get
		*/
		function configureFromGet ($get)
		{
			$query = '';
			
			if (isset($get['q'])) {
				if (isset($get['pre_q'])) {
					$query = $get['pre_q'].' ';
				}
				$query .= $get['q'];
				
				$this->setQuery($query);
			}

				
			// exact match
			if (!empty($get['quoteQuery'])) {
				$this->setQuoteQuery($get['quoteQuery']);
			}
		
			// 'or' match
			if (!empty($get['orQuery'])) {
				$this->setOrQuery($get['orQuery']);
			}
			
			if (!empty($query) || !empty($this->orQuery) || !empty($this->quoteQuery)) {	
					
				// exclude words
				if (!empty($get['excludeWords'])) {
					$this->setExcludeWords($get['excludeWords']);
				}
		
				// file format
				if (!empty($get['fileFormat'])) {
					
					// UI may allow multiple selections
					if (is_array($get['fileFormat'])) {
						foreach ($get['fileFormat'] as $fileFormat) {
							$this->addFileFormat($fileFormat);
						}
					}
					else {
						$this->addFileFormat($get['fileFormat']);
					}
				}
				
				if (!empty($get['fileFormatInclusion'])) {
					$this->setFileFormatInclusion($get['fileFormatInclusion']);
				}
			}
			
			
			if (!empty($get['startNum'])) {
				$this->setStartNum($get['startNum']);
			}
			
			if (!empty($get['numToShow'])) {
				$this->setNumToShow($get['numToShow']);
			}
		
			if (!empty($get['sortBy'])) {
				$this->setSortBy($get['sortBy']);
			}	
			if (!isset($get['filter'])) {
				$this->setFilterResults($this->defaultFilter);
			}
			else {
				$this->setFilterResults($get['filter']);
			}	
		}
		
		/**
		* Sets the appliance to use for this search as the first available live appliance.
		*/
		function setAppliance ()
		{
			$appliances = getRupaAppliances('live', '1');
			if (sizeof($appliances) > 0) {
				$this->appliance = $appliances[0];
			}
		}
		
		/**
		* Sets the appliance to use as a custom appliance.
		* @param RupaAppliance $app The appliance to set
		*/
		function setCustomAppliance ($app)
		{
			$this->appliance = $app;
		}
		
		/**
		* Performs a search with the current settings
		* @return RupaSearchResult
		*/
		function search ()
		{
			$searchResults = null;

			if ($this->appliance == null) {
				$this->setAppliance();
				return $this->search();
			}			
			else {
				$sites = $this->getSites();
				if (empty($this->appliance->defaultCollection) && $this->allSites === true) {
					$sites = array();
				}
				else if (sizeof($this->getSites()) == 0) {
					$sites = array($this->appliance->defaultCollection);
				}
				
				$queryString = $this->appliance->buildSearchQuery(
										$this->getFullQuery(), 
										$this->getStartNum(), 
										$this->getNumToShow(), 
										$sites, 
										$this->getMetaFields(), 
										$this->getMetaValues(),
										$this->getMetadataFieldsNames(),
										$this->getSortBy(),
										-1,
										$this->filterResults);
										
				$xmlResults = $this->appliance->requestResults($queryString);
				$searchResults = $this->appliance->parseResults($xmlResults);
			}

			return $searchResults;
		}
		
		/**
		* Converts $this->sites into a string with each site separated by a pipe '|' and then 
		* enclosed in brackets.
		*
		* @return string The string of sites.
		*/
		function getSiteString ()
		{
			$siteString = '';
			
			$sitesSize = sizeof($this->sites);
			
			for ($j = 0; $j < $sitesSize; $j++) {
				$siteString .= $this->sites[$j];
				
				if ($sitesSize > 0 && $j < $sitesSize - 1) {
					$siteString .= '|';
				}
			}
			
			return '('.$siteString.')';
		}
		
		/**
		* Adds the given site to the list of sites to search.
		* @param string $siteName The site to add.
		*/
		function addSite ($siteName)
		{
			$this->sites[] = $siteName;
		}
		
		/**
		* Adds the name of a metadata field to the metadata restrictions for
		* this search.
		* @param string $partialFieldName The metadata name to add
		*/
		function addPartialFieldsName ($partialFieldName)
		{
			$this->partialFieldsNames[] = $partialFieldName;
		}
		
		/**
		* Adds a value to look for in the metadata restrictions. These must be
		* added in the same order as the names.
		* @param string $partialFieldsValue The value to add.
		*/
		function addPartialFieldsValue ($partialFieldsValue)
		{
			$this->partialFieldsValues[] = $partialFieldsValue;
		}
		
		/**
		* Adds the given metadata field name to the list of fields to be 
		* requested in this search.
		* @param string $metadataFieldName
		*/
		function addMetadataFieldsName ($metadataFieldName)
		{
			$this->metadataFieldsNames[] = $metadataFieldName;
		}
		
		/**
		* Sets words to be excluded from this search.
		* @param string $excludeWords
		*/
		function setExcludeWords ($excludeWords)
		{
			$this->excludeWords = trim($excludeWords);
			$this->fullQuery = '';
		}
		
		/**
		* Adds the given file format to the list of file formats to restrict
		* the search by,
		* @param string $fileFormat The file format to add
		*/
		function addFileFormat ($fileFormat)
		{
			$this->fileFormats[] = $fileFormat;
			$this->fullQuery = '';
		}
		
		/**
		* Set this to '-' to exclude all file formats specified.
		* @param string $fileFormatInclusion '-' to exlude or '' to restrict to all specified file formats
		*/
		function setFileFormatInclusion ($fileFormatInclusion)
		{
			$this->fileFormatInclusion = $fileFormatInclusion;
			$this->fullQuery = '';
		}
		
		/**
		* Sets the number of results to attempt to retrieve.
		* @param integer $numToShow
		*/
		function setNumToShow ($numToShow)
		{
			$this->numToShow = $numToShow;
		}
		
		/**
		* Empties all query components
		*/
		function clearFullQuery ()
		{
			$this->query = '';
			$this->quoteQuery = '';
			$this->orQuery = '';
			$this->excludeWords = '';
			$this->fileFormatInclusion = '';
			$this->fileFormats = array();
			$this->fullQuery = '';
		}
		
		/**
		* Set the search query
		* @param string $query
		*/
		function setQuery ($query)
		{			
			$this->query = $query;
			$this->fullQuery = '';
		}
		
		/**
		* Set phrase terms
		* @param string $quoteQuery
		*/
		function setQuoteQuery ($quoteQuery)
		{
			$this->quoteQuery = trim($quoteQuery);
			$this->fullQuery = '';
		}
		
		/**
		* Set or terms (any of which may appear in a result)
		* @param string $orQuery
		*/
		function setOrQuery ($orQuery)
		{
			$this->orQuery = trim($orQuery);
			$this->fullQuery = '';
		}
		
		/**
		* Sets the offset at which to start the result set using a 1-index (e.g. for page 2 this would be set to 11)
		* @param integer $startNum
		*/
		function setStartNum ($startNum)
		{
			$this->startNum = $startNum;
		}
		
		/**
		* If set to 'date' results will be sorted by date, otherwise they will be sorted by relevance.
		* @param string $sortBy
		*/
		function setSortBy ($sortBy)
		{
			$this->sortBy = $sortBy;
		}
		
		/**
		* Retrieves the query for this search (this does not include advanced 
		* elements such as quoteQuery and orQuery).
		* @return string
		*/
		function getQuery ()
		{
			return $this->query;
		}
		
		/**
		* Retrieves the full query for this search, incuding all advanced elements.
		* @return string
		*/
		function getFullQuery ()
		{
			if ($this->fullQuery == '') {
			
				$queryString = '';
				
				if ($this->query != '') {
					$queryString .= $this->query;
				}
				
				if ($this->quoteQuery != '') {
					$queryString .= ' "'.$this->quoteQuery.'" ';
				}
				
				if ($this->orQuery != '') {
					
					$orWords = explode(' ', $this->orQuery);
					$queryString .= ' ';
													
					for ($j = 0; $j < sizeof($orWords); $j++) {
						if (trim($orWords[$j]) != '') {
							if ($j > 0) {
								$queryString .= ' OR ';
							}
							
							$queryString .= trim($orWords[$j]);
						}
					}
				}
				
				if ($this->excludeWords != '') {
					$excludeTokens = explode(' ', $this->excludeWords);
					
					for ($j = 0; $j < sizeof($excludeTokens); $j++) {
						if (trim($excludeTokens[$j]) != "")
						{
							$queryString .= ' -'.trim($excludeTokens[$j]).' ';
						}
					}
				}
				
				$filetypePrefix = '';
				
				if ($this->fileFormatInclusion == '-') {
					$filetypePrefix = ' -';
				}
				else {
					$filetypePrefix = ' ';
				}
							
				for ($j = 0; $j < sizeof($this->fileFormats); $j++)
				{
					if ($j > 0 && $this->fileFormatInclusion != '-') {
						$queryString .= ' OR';
					}
				
					$queryString .= $filetypePrefix . 'filetype:';
				
					switch ($this->fileFormats[$j]) {
						case 'pdf':
							$queryString .= 'pdf';
							break;
						case 'doc':
							$queryString .= 'doc';
							break;
						case 'xls':
							$queryString .= 'xls';
							break;
						case 'ppt':
						case 'pps':
							$queryString .= 'ppt';
							break;
						case 'rtf':
							$queryString .= 'rtf';
							break;
					}			
				}
				
				$this->fullQuery = $queryString;
				
			}
			else {
				//cache subsequent calls for this instance
				$queryString = $this->fullQuery;
			}
			
			return str_replace("''", "'", $queryString);
		}
		
		/**
		* Returns an HTML encoded version of the full query for this search.
		* @return string
		*/
		function getFullQueryForXHTML ()
		{
		
			return encodeHtml($this->getFullQuery());
		}
		
		/**
		* Returns a URL encoded version of the full query for this search.
		* @return string
		*/
		function getFullQueryForURL ()
		{
			return urlencode($this->getFullQuery());
		}
		
		/**
		* Returns the categoryIDs as an HTTP GET string argument.
		* @return string The categoryIDs in &categoryIDs[]= format
		*/
		function getCategoryIDsAsString ()
		{
			$categoryIDString = '';
			
			if (sizeof($this->categoryIDs) > 0 ) {
				$categoryIDString = '&categoryIDs[]='.implode('&categoryIDs[]=', $this->categoryIDs);
			}
			
			return $categoryIDString;
		}
		
		/**
		* Returns an HTML encoded category IDs GET string
		* @return string
		*/
		function getCategoryIDsAsStringForXHTML ()
		{
			return encodeHtml($this->getCategoryIDsAsString());
		}
		
		/**
		* Returns a URL encoded category IDs GET string
		* @return string
		*/
		function getCategoryIDsAsStringForURL ()
		{
			return urlencode($this->getCategoryIDsAsString());
		}
		
		/**
		* Retruns the current content of $this->partialFieldsNames as a string.
		* @param string $separator A string to separate each item with, defaults to ','
		* @param boolean $prepend Whether or not to prepend the string with $separator
		* @return string
		*/
		function getPartialFieldsNamesAsString ($separator = ',', $prepend = false)
		{
			$pfnString = '';
			
			// only build a string if we're not searching all sites
			if (!empty($this->partialFieldsNames)) {
				$pfnString = implode($separator, $this->partialFieldsNames);
				
				if ($prepend) {
					$pfnString = $separator.$pfnString;
				}
			}
			
			return $pfnString;
		}
		
		/**
		* Retruns the current content of $this->partialFieldsNames as a  url-encoded string.
		* @param string $separator A string to separate each item with, defaults to ','
		* @param boolean $prepend Whether or not to prepend the string with $separator
		* @return string
		*/
		function getPartialFieldsNamesAsStringForURL ($separator = ',', $prepend = false)
		{
			$pfnString = '';
			
			// only build a string if we're not searching all sites
			if (!empty($this->partialFieldsNames)) {
				foreach ($this->partialFieldsNames as $pfn) {
					$pfnString .= $separator.urlencode($pfn);
				}
				
				if ($prepend) {
					$pfnString = $separator.$pfnString;
				}
			}
			
			return $pfnString;
		}
		
		/**
		* Retruns the current content of $this->partialFieldsValues as a string.
		* @param string $separator A string to separate each item with, defaults to ','
		* @param boolean $prepend Whether or not to prepend the string with $separator
		* @return string
		*/
		function getPartialFieldsValuesAsString ($separator = ',', $prepend = false)
		{
			$pfvString = '';
			
			// only build a string if we're not searching all sites
			if (!empty($this->partialFieldsValues)) {
				$pfvString = implode($separator, $this->partialFieldsValues);
				
				if ($prepend) {
					$pfvString = $separator.$pfvString;
				}
			}
			
			return $pfvString;
		}
		
		/**
		* Retruns the current content of $this->partialFieldsValues as a  url-encoded string.
		* @param string $separator A string to separate each item with, defaults to ','
		* @param boolean $prepend Whether or not to prepend the string with $separator
		* @return string
		*/
		function getPartialFieldsValuesAsStringForURL ($separator = ',', $prepend = false)
		{
			$pfvString = '';
			
			// only build a string if we're not searching all sites
			if (!empty($this->partialFieldsValues)) {
				foreach ($this->partialFieldsValues as $pfv) {
					$pfvString .= $separator.urlencode($pfv);
				}
							
				if ($prepend) {
					$pfvString = $separator.$pfvString;
				}
			}
			
			return $pfvString;
		}
		
		/**
		* Returns the the full HTTP GET query string which can be used to 
		* identify this search. This includes the search query, start number, 
		* number of results to show, sites being searched and sort options.
		* @return string
		*/
		function getRupaQueryString ()
		{
			$query =	'q='.$this->getFullQuery().
						$this->getCategoryIDsAsString().
						$this->getSitesAsString('&sites[]=', true).
						$this->getPartialFieldsNamesAsString('&partialFieldsNames[]=').
						$this->getPartialFieldsValuesAsString('&partialFieldsValues[]=');
			
			if ($this->startNum != $this->defaultStartNum) {
				$query .= '&startNum='.$this->startNum;
			}
			
			if ($this->numToShow != $this->defaultNumToShow) {
				$query .= '&numToShow='.$this->numToShow;
			}
			
			if ($this->sortBy != $this->defaultSortBy) {
				$query .= '&sortBy='.$this->sortBy;
			}
			
			if ($this->filterResults != $this->defaultFilter) {
				$query .= '&filter=' . $this->filterResults;
			}
		
			return $query;
		}
		
		/**
		* Returns an HTML encoded HTTP GET query string for this search
		* @return string
		*/
		function getRupaQueryStringForXHTML ()
		{
			$query =	'q='.rawurlencode($this->getFullQueryForXHTML()).
						$this->getCategoryIDsAsStringForXHTML().
						$this->getSitesAsStringForXHTML('&sites%5B%5D=', true);
			
			if ($this->startNum != $this->defaultStartNum) {
				$query .= '&amp;startNum='.$this->startNum;
			}
			
			if ($this->numToShow != $this->defaultNumToShow) {
				$query .= '&amp;numToShow='.$this->numToShow;
			}
			
			if ($this->sortBy != $this->defaultSortBy) {
				$query .= '&amp;sortBy='.urlencode(encodeHtml($this->sortBy));
			}
			
			if ($this->filterResults != $this->defaultFilter) {
				$query .= '&amp;filter=' . $this->filterResults;
			}
		
			return $query;
		}
		
		/**
		* Returns a URL encoded HTTP GET query string for this search
		* @return string
		*/
		function getRupaQueryStringForURL ()
		{
			$query =	'q='.$this->getFullQueryForURL().
						$this->getCategoryIDsAsStringForURL().
						$this->getSitesAsStringForURL('&sites[]=').
						$this->getPartialFieldsNamesAsStringForURL('&partialFieldsNames[]=').
						$this->getPartialFieldsValuesAsStringForURL('&partialFieldsValues[]=');
			
			if ($this->startNum != $this->defaultStartNum) {
				$query .= '&startNum='.$this->startNum;
			}
			
			if ($this->numToShow != $this->defaultNumToShow) {
				$query .= '&numToShow='.$this->numToShow;
			}
			
			if ($this->sortBy != $this->defaultSortBy) {
				$query .= '&sortBy='.$this->sortBy;
			}
			
			if ($this->filterResults != $this->defaultFilter) {
				$query .= '&filter=' . $this->filterResults;
			}
		
			return $query;
		}
		
		/**
		* Retruns the current content of $this->sites as a string.
		* @param string $separator A string to separate each item with, defaults to ','
		* @param boolean $prepend Whether or not to prepend the string with $separator
		* @return string
		*/
		function getSitesAsString ($separator = ',', $prepend = false)
		{
			$siteString = '';
			
			// only build a string if we're not searching all sites
			if (!$this->allSites && sizeof($this->sites) > 0) {
				$siteString = implode($separator, $this->sites);
				
				if ($prepend) {
					$siteString = $separator.$siteString;
				}
			}
			
			return $siteString;
		}
		
		/**
		* Returns an HTML encoded site string
		* @param string $separator A string to separate each item with, defaults to ','
		* @param boolean $prepend Whether or not to prepend the string with $separator
		* @return string
		*/
		function getSitesAsStringForXHTML ($separator = ',', $prepend = false)
		{
			return encodeHtml($this->getSitesAsString($separator, $prepend));
		}
		
		/**
		* Returns a URL encoded site string
		* @param string $separator A string to separate each item with, defaults to ','
		* @param boolean $prepend Whether or not to prepend the string with $separator
		* @return string
		*/
		function getSitesAsStringForURL ($separator = ',', $prepend = false)
		{
			$siteString = '';
			
			// only build a string if we're not searching all sites
			if (!$this->allSites && sizeof($this->sites) > 0) {
				foreach ($this->sites as $site) {
					$siteString .= $separator.urlencode($site);
				}
				
				if ($prepend) {
					$siteString = $separator.$siteString;
				}	
			}
			
			return $siteString;
		}
		
		/**
		* Returns the partialFieldsNames for this search.
		* @return array[]string
		*/
		function getMetaFields ()
		{
			return $this->partialFieldsNames;
		}
		
		/**
		* Returns the partialFieldsValues for this search.
		* @return array[]string
		*/
		function getMetaValues ()
		{
			return $this->partialFieldsValues;
		}
		
		/**
		* Returns the metadataFieldsNames array for this search.
		* @return array[]string
		*/
		function getMetadataFieldsNames ()
		{
			return $this->metadataFieldsNames;
		}
		
		/**
		* Returns a string containing the getMetadataFieldsNames for this search.
		* @param string $separator The string to insert between each item
		* @param boolean $prepend Whether or not to prepend the string with the separator
		* @return string
		*/
		function getMetadataFieldsNamesAsString ($separator = ',', $prepend = false)
		{
			$mfnString = '';
			
			if (!empty($this->metadataFieldsNames)) {
				$mfnString = implode($separator, $this->metadataFieldsNames);
				
				if ($prepend) {
					$mfnString = $separator.$mfnString;
				}
			}
			
			return $mfnString;
		
		}
		
		/**
		* Returns the number of results requested.
		* @return integer
		*/
		function getNumToShow ()
		{
			return $this->numToShow;
		}
		
		/**
		* Returns the array of sites.
		* @return array[]string
		*/
		function getSites ()
		{
			return $this->sites;
		}
		
		/**
		* Returns the sort string
		* @return string
		*/
		function getSortBy ()
		{
			return $this->sortBy;
		}
		
		/**
		* Returns the result offset number
		* @return integer
		*/
		function getStartNum ()
		{
			return $this->startNum;
		}

		/**
		* Empties the sites assigned to this search
		*/
		function clearSites ()
		{
			$this->sites = array();
			$this->allSites = false;
		}
		
		/**
		* Empties partialFieldsValues and partialFieldsNames
		*/
		function clearPartialFields ()
		{
			$this->partialFieldsNames = array();
			$this->partialFieldsValues = array();
		}
		
		function clearMetadataFields ()
		{
			$this->metadataFieldNames = array();
		}
		
		/**
		* Returns a clone of this RupaSearch object. This is a .Net helper function
		* @return RupaSearch
		*/
		function getClone ()
		{			
			$rupaSearch = new RupaSearch();
			$rupaSearch->appliance = $this->appliance;
			$rupaSearch->collectionNames = $this->collectionNames;
			$rupaSearch->query = $this->query;
			$rupaSearch->excludeWords = $this->excludeWords;
			$rupaSearch->orQuery = $this->orQuery;
			$rupaSearch->quoteQuery = $this->quoteQuery;
			$rupaSearch->startNum = $this->startNum;
			$rupaSearch->numToShow = $this->numToShow;
			$rupaSearch->sortBy = $this->sortBy;
			$rupaSearch->categoryIDs = $this->categoryIDs;
			$rupaSearch->sites = $this->sites;
			$rupaSearch->fullQuery = $this->fullQuery;
			$rupaSearch->partialFieldsNames = $this->partialFieldsNames;
			$rupaSearch->partialFieldsValues = $this->partialFieldsValues;
			$rupaSearch->metadataFieldsNames = $this->metadataFieldsNames;
			$rupaSearch->fileFormats = $this->fileFormats;
			$rupaSearch->fileFormatInclusion = $this->fileFormatInclusion;
			$rupaSearch->allSites = $this->allSites;
			$rupaSearch->collections = $this->collections;
			$rupaSearch->outputFormat = $this->outputFormat;
			$rupaSearch->filterResults = $this->filterResults;
			$rupaSearch->defaultFilter = $this->defaultFilter;
			
			return $rupaSearch;
		}

		/**
		 * Returns the status of the filterResults flag
		 * 
		 * @return string The value of the filterResults flag
		 */
		function getFilterResults ()
		{
			return $this->filterResults;
		}
		
		/**
		 * Sets the default value for filterResults. Sets to FILTER_OFF
		 * if an invalid filter is given.
		 * @param string $filter One of the filter options
		 */
		function setDefaultFilter ($filter)
		{
			switch ($filter) {
				case FILTER_OFF:
				case FILTER_SNIPPET_ONLY:
				case FILTER_SNIPPET_AND_DIRECTORY:
				case FILTER_DIRECTORY_ONLY:
					$this->defaultFilter = $filter;
					break;
				default:
					$this->defaultFilter = FILTER_OFF;
					break;
			}
		}
		
		/**
		 * Sets the status of the filterResults flag
		 * 
		 * @param string $filterResults The value to set for the filterResults flag
		 */
		function setFilterResults ($filterResults)
		{
			switch ($filterResults) {
				case FILTER_OFF:
				case FILTER_SNIPPET_ONLY:
				case FILTER_SNIPPET_AND_DIRECTORY:
				case FILTER_DIRECTORY_ONLY:
					$this->filterResults = $filterResults;
					break;
				default:
					$this->filterResults = FILTER_OFF;
					break;
			}
		}
		
	}
	
	/**
	* Alias of getAllAdvancedSearchRupaCollections(). Previously returned
	* contents of $JADOOGLE_COLLECTIONS global.
	*
	* @return array[]RupaCollection Array of collections.
	*/
	function getRupaAdvancedSiteSearchCollections ()
	{
		return getAllAdvancedSearchRupaCollections();
	}
