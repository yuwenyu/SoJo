<?php
/**
 * @namespace Core
 *
 * @author YWY
 * @copyright Copyright (c) 2015~2***
 */
 
namespace Core\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Core\Service\AdapterSojo;

class FactoryAdapterSojo implements FactoryInterface
{
	/**
	 * Create service
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 *
	 * @return mixed
	 */
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new AdapterSojo(array(
			'driver'         => 'Pdo',
			'dsn'            => 'mysql:dbname=sojo;host=127.0.0.1',
			'driver_options' => array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''),
			'username' 		 => 'root',
			'password' 		 => 'root'
		));
	}
}