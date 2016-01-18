<?php 
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Administrator\View\Helper\CoreHelper;

class FactoryHelperCore implements FactoryInterface
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
		return new CoreHelper(
			$serviceLocator->getServiceLocator()->get('Administrator\Service\CoreServiceInterface'),
			$serviceLocator->getServiceLocator()->get('Administrator\Service\UserServiceInterface'),
			$serviceLocator->getServiceLocator()->get('Administrator\Model\MenuModelInterface'),
			$serviceLocator->getServiceLocator()->get('Administrator\Model\AuthorityModelInterface'),
			$serviceLocator->getServiceLocator()->get('Administrator\Model\RoleModelInterface'));
	}
}