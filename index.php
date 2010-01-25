<?php 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
?>

<?php
//bootstrap
ob_start();
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');

//detect a given controller
if(!empty($_GET['c'])) define('CTRL', strtolower(strval($_GET['c'])));
else define('CTRL', 'main');

//detect a given action
if(!empty($_GET['a'])) define('ACTN', strtolower(strval($_GET['a'])));
else define('ACTN', 'index');

//instanciate called Controller
$base = 'Controller_' . CTRL;
try {
	if (!class_exists($base))
		throw new Exception('Controller ' . CTRL . ' not found', 100);
	else
		$Ctrl = new $base;
	
}
catch(Exception $e) {
	Framework_Utility_Exception::handle($e);
}

//call the given action
$functions = get_class_methods(&$Ctrl);
try {
	if (!in_array(ACTN, $functions)) {
		throw new Exception('Called action ' . ACTN . ' of Controller ' . CTRL . ' not found', 110);
		die('Framework Exception: called action not found');
	}
	call_user_func(array(&$Ctrl, ACTN));
}
catch(Exception $e) {
	Framework_Utility_Exception::handle($e);
}


define('LAYOUT', $Ctrl->getLayout());
define('CONTENT_TO_VIEW', TEMPLATE_PATH . DS . $Ctrl->getView() . TPL_FILETYPE);

//return the setted variables by the controller
$variables = $Ctrl->getSettedValues();
foreach($variables AS $name => $value) {
	$$name = $value;
}

//get the HTML helper class
$html = new Framework_Utility_Html;

//tsrat the output procedure
require_once(LAYOUT_FOLDER . DS . LAYOUT . TPL_FILETYPE);