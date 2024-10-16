<?php
	include_once('JaduConstants.php');
	include_once('library/JaduStringFunctions.php');
	
	$imageDirectory = $SECURE_SERVER . "/images/";

	/**
	* Adds the javascript files required for the document editor
	*/
	function addDocumentEditor()
	{
		addJavascript('xinha/my_config.php');
		addJavascript('xinha/rangy/rangy-core.js');
		addJavascript('xinha/rangy/rangy-cssclassapplier.custom.js');
		addJavascript('xinha/XinhaCore.js');    
		addCSS('css/editor.css');
	}
	
	/**
	* builds a string for a GET query of the format '&amp;' . name .'='.value from the vars array
	*
	* @param Array $vars an array of the format $name => $value
	* @return String the generated GET string
	*/
	function buildGetString ($vars)
	{
		return http_build_query($vars, '', '&amp;');
	}
