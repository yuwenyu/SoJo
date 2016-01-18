<?php
$ini = new Zend\Config\Reader\Ini();
$iniConfiguration = $ini->setNestSeparator('::')->fromFile(__DIR__ . '/' . 'm.ini');

$db = array(
	'driver'         => 'Pdo',
	'dsn'            => 'mysql:dbname=sojo_core;host=127.0.0.1',
	'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''),
	'username' 		 => 'root',
	'password' 		 => 'root'
);

$view_manager = array(
	'display_not_found_reason' 	=> true,
	'display_exceptions'       	=> true,
	'doctype'                  	=> 'HTML5',
	'not_found_template'       	=> 'error/404',
	'exception_template'       	=> 'error/exc',
	'template_map' 				=> array(
		'error/404'    		=> __DIR__ . '/../view/error/404.phtml',
		'error/exc'  		=> __DIR__ . '/../view/error/exc.phtml',
		'layout/layout'		=> __DIR__ . '/../view/layout/blank.phtml'
	),
	'template_path_stack' 		=> array(
		'administrator'	=> __DIR__ . '/../view')
);

return array_merge($iniConfiguration, compact('db','view_manager'));