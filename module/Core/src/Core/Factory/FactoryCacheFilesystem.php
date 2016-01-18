<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */
namespace Core\Factory;

use Zend\Cache\StorageFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FactoryCacheFilesystem implements FactoryInterface
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
		$config = $serviceLocator->get('config');
		
		if ($config['fcache']['is_open'])
		{
			return StorageFactory::factory(array(
				'adapter' => array('name'=>'filesystem','options'=>$config['fcache_manager']),
				'plugins' => array('exception_handler' => array(
								   'throw_exceptions' => false),'serializer')
			));
		}
		else
		{
			return false;
		}
	}
}