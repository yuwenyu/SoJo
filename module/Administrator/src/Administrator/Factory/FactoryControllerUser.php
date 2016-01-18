<?php 
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Factory;

use Administrator\Controller\UserController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FactoryControllerUser implements FactoryInterface
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
		return new UserController(
			$serviceLocator->getServiceLocator()->get('Administrator\Model\AdministratorModelInterface'),
			$serviceLocator->getServiceLocator()->get('Administrator\Service\UserServiceInterface'),
			$serviceLocator->getServiceLocator()->get('Administrator\Service\CoreServiceInterface'));
	}
}