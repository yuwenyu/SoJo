<?php 
/**
 * @namespace Sojo
 *
 * @author Kyle Yu
 * @copyright Copyright (c) 2016-2020 Sojo Inc.
 */

namespace Sojo\Factory;

use Sojo\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;

class FactoryControllerIndex implements FactoryInterface
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
		return new IndexController(
			new TableGateway('test', $serviceLocator->getServiceLocator()->get('Core\Service\AdapterSojo'))
		);
	}
}