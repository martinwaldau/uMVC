<?php 
/**
 * Autoloader loads files from the libraray automatically
 * @param string $class_name
 * @return null
 */
function customAutoload($class_name) {
	$cname = strtolower($class_name);
	$file_name = NULL;
	$fileexists = FALSE;
		
	$file_name = 'lib' . DS . str_replace('_', DS, $cname) . '.php';
		
	if(file_exists($file_name)) {
		$fileexists = TRUE;
		require_once($file_name);
	}
	
}

spl_autoload_register('customAutoload');
spl_autoload_extensions('.php');