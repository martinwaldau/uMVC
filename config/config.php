<?php
define('DS', DIRECTORY_SEPARATOR);
//the document root
define('ROOT', dirname(dirname(__FILE__)));

//Charset
define('CHARSET', 'UTF-8');

//database stuff
define('DATABASE_HOST', 'localhost');
define('DATABASE_NAME', 'tobi');
define('DATABASE_USER', 'root');
define('DATABASE_PASSWORD', '');

//template stuff
define('TPL_FILETYPE', '.phtml');
define('MAIN_TEMPLATE', 'index');
define('TEMPLATE_PATH', 'templates');
define('LAYOUT_FOLDER', TEMPLATE_PATH . DS . 'layouts');



//load autolaoder for classess
require_once(realpath('lib' . DS . 'framework' .  DS . 'autoload.php'));
//set include paths
set_include_path(ROOT . PATH_SEPARATOR . get_include_path());