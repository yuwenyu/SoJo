<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */
namespace Core\Factory;

use Core\Service\C;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FactoryC implements FactoryInterface
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
		$aCacheAdapter = array('__c_f' => $serviceLocator->get('Zend\Cache\Storage\Adapter\Filesystem'),
							   '__c_r' => $serviceLocator->get('Zend\Cache\Storage\Adapter\Redis'));
		return new C($aCacheAdapter, $serviceLocator->get('Zend\Db\Adapter\Adapter'));
	}
}