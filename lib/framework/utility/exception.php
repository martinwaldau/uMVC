<?php
Class Framework_Utility_Exception {
	const baseError = '<h1>Framework Exception</h1>';
	
	public static function handle(Exception $e) {
		
		echo self::baseError;
		echo '<h2>' . $e->getMessage() . '</h2>';
		echo '<p>in Line: ' . $e->getLine() . '<br />';
		echo 'File: ' . $e->getFile() . '</p>';
		echo '<h3>Trace</h3>';
		echo '<p>' . $e->getTraceAsString() . '</p>';
		
		die();
	}
	
}