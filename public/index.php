<?php

// Define path to application directory
defined('APPLICATION_PATH')
	|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
	|| define('APPLICATION_ENV', 
	(getenv('APPLICATION_ENV') ? 
	getenv('APPLICATION_ENV') : 'development'));
	
// Add Zend to include path when in production
$include = NULL;

if(APPLICATION_ENV == 'production') {
	$include = realpath(APPLICATION_PATH . '/../../Zend-Framework/Zend-1.12.3');
}

// Ensure application library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(APPLICATION_PATH . '/../library'),
	$include,
	get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
	APPLICATION_ENV,
	APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
			->run();