<?php
/**
 * HTML Helper class
 * provides some helper functions for HTML output
 * @author skyfyre info@mwweb.de
 *
 */
Class Framework_Utility_Html {
	
	public function __construct() {
		
	}
	
	/**
	 * Wandelt die Sonderzeichen des gegebenen Strings mittels htmlentities() um
	 * @param string $string
	 * @return string
	 */
	public function e($string) {
		return htmlentities($string, ENT_QUOTES, CHARSET);
	}
}