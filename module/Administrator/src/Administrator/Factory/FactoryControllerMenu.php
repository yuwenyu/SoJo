<?php 
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Factory;

use Administrator\Controller\MenuController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FactoryControllerMenu implements FactoryInterface
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
		return new MenuController(
			$serviceLocator->getServiceLocator()->get('Administrator\Model\Menu'),
			$serviceLocator->getServiceLocator()->get('Administrator\Form\MenuForm'),
			$serviceLocator->getServiceLocator()->get('Administrator\Model\MenuInfo'),
			$serviceLocator->getServiceLocator()->get('Administrator\Form\MenuInfoForm'),
			$serviceLocator->getServiceLocator()->get('Administrator\Model\MenuModelInterface'),
			$serviceLocator->getServiceLocator()->get('Administrator\Service\CommonServiceInterface'),
			$serviceLocator->getServiceLocator()->get('Administrator\Service\CoreServiceInterface'));
	}
}