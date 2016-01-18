<?php 
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Factory;

use Administrator\Controller\RoleController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FactoryControllerRole implements FactoryInterface
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
		return new RoleController(
			$serviceLocator->getServiceLocator()->get('Administrator\Model\Role'),
			$serviceLocator->getServiceLocator()->get('Administrator\Form\RoleForm'),
			$serviceLocator->getServiceLocator()->get('Administrator\Model\RoleAuthorities'),
			$serviceLocator->getServiceLocator()->get('Administrator\Form\RoleAuthoritiesForm'),
			$serviceLocator->getServiceLocator()->get('Administrator\Service\CoreServiceInterface'),
			$serviceLocator->getServiceLocator()->get('Administrator\Service\CommonServiceInterface'),
			$serviceLocator->getServiceLocator()->get('Administrator\Model\RoleModelInterface'),
			$serviceLocator->getServiceLocator()->get('Administrator\Model\AuthorityModelInterface')
		);
	}
}