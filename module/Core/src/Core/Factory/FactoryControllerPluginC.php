<?php
/**
 * Self::Framework by Zend
*
* @author WenYu
* @copyright Copyright (c) 2015~2***
*/

namespace Core\Factory;

use Core\Controller\Plugins\CPlugin;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FactoryControllerPluginC implements FactoryInterface
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
		return new CPlugin($serviceLocator->getServiceLocator()->get('Core\Service\C'));
	}
}