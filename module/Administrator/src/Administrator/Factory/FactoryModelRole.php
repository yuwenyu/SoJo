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
use Zend\Db\TableGateway\TableGateway;

use Administrator\Model\RoleModel;

class FactoryModelRole implements FactoryInterface
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
		return new RoleModel(
			   new TableGateway('roles', $serviceLocator->get('Zend\Db\Adapter\Adapter')),
			   new TableGateway('role_authority', $serviceLocator->get('Zend\Db\Adapter\Adapter')),
			   $serviceLocator->get('Administrator\Service\CoreServiceInterface'),
			   $serviceLocator->get('Administrator\Service\CommonServiceInterface'));
	}
}