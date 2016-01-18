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

use Administrator\Controller\AclController;

class FactoryControllerAcl implements FactoryInterface
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
		return new AclController(
			$serviceLocator->getServiceLocator()->get('Administrator\Model\Acl'),
			$serviceLocator->getServiceLocator()->get('Administrator\Form\AclForm'),
			$serviceLocator->getServiceLocator()->get('Administrator\Service\CoreServiceInterface'),
			$serviceLocator->getServiceLocator()->get('Administrator\Service\CommonServiceInterface'),
			$serviceLocator->getServiceLocator()->get('Administrator\Model\AuthorityModelInterface'));	
	}
}