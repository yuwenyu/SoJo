<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Authentication\AuthenticationService;
use Zend\Config\Reader\Ini;

class Module
{
	public function onBootstrap(MvcEvent $e)
	{
		date_default_timezone_set('UTC');
		
		$eventManager = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);
		
		$serviceManager	= $e->getApplication()->getServiceManager();
		$serviceConfigManager = $serviceManager->get('config');
		
		if (isset($serviceConfigManager['router']['routes'][$_SERVER['HTTP_HOST']])) 
		{
			$serviceAdministratorConfigManager = $serviceConfigManager['router']['routes'][$_SERVER['HTTP_HOST']];
			if (isset($serviceAdministratorConfigManager['options']['constraints']['4th']) && 
			   		 ($serviceAdministratorConfigManager['options']['constraints']['4th'] === strtolower(__NAMESPACE__)))
			{
				$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onAdministratorPreDispatch'), 100);
				$this->administratorBootstrap($e, $eventManager, $serviceManager,
											  $serviceAdministratorConfigManager);
			}
		}
	}
	
	public function onAdministratorPreDispatch(MvcEvent $e)
	{
		/**
		 * @access Initialize the Paramters
		 * @return array
		 */
		$cacheFileSystem = $e->getApplication()->getServiceManager()->get('Zend\Cache\Storage\Adapter\Filesystem');
		if ($cacheFileSystem == false)
		{
			$e->getApplication()->getServiceManager()->get('ControllerPluginManager')
			  ->get('CoreCPlugin')->setLog('Administrator|Module', 'onAdministratorPreDispatch', 
			  		'Fatal Error :: Administrator|Module Line 51 - Cache Adapter FileSystem Callback False');
			exit('Fatal Error : Administrator|Module');
		}
		$cacheFileSystem->getOptions()->setNamespace(__NAMESPACE__);
		$cacheFileSystem->getOptions()->setTtl(864000);
		
		if (!$cacheFileSystem->hasItem('StaticParameters'))
		{
			$ini = new Ini();
			$iniParameters = $ini->setNestSeparator('::')->fromFile(__DIR__.'/config/p.ini');
			$cacheFileSystem->setItem('StaticParameters',serialize($iniParameters));
		}
	}
	
	public function administratorBootstrap($e, $eventManager, $serviceManager, 
										   $serviceAdministratorConfigManager)
	{
		/**
		 * @access Authentication Login or Logout Route Redirect
		 * @return resource
		 */
		
		$authenticationService = new AuthenticationService();
		$authenticationServiceStorage = $authenticationService->getIdentity();
		
		if (!isset($authenticationServiceStorage->id) || empty($authenticationServiceStorage->id))
		{
			$response = $e->getResponse();
				
			$eventManager->attach(
				MvcEvent::EVENT_ROUTE, function ($event) use
												($response, $serviceAdministratorConfigManager)
				{
					$routeMatch = $event->getRouteMatch()->getParams();
		
					if (($routeMatch['controller'] == 'Administrator\Controller\User' &&
							 $routeMatch['action'] != 'login') ||
						($routeMatch['controller'] != 'Administrator\Controller\User' &&
						 $routeMatch['controller'] != 'Administrator\Controller\Api' ))
					{
						$routerOptions = array();
							
						if ($serviceAdministratorConfigManager['options']['constraints'])
						{
							foreach ($serviceAdministratorConfigManager['options']['constraints'] as $constraint => $v)
							{
								if (in_array($constraint, array('4th','3rd','2nd','1st')))
								{
									$routerOptions[$constraint] = $v;
								}
							}
		
							$routerOptions['controller'] = 'User';
							$routerOptions['action'] = 'login';
						}
							
						$response->getHeaders()->addHeaderLine('Location',$event->getRouter()->assemble($routerOptions,
								array('name' => $_SERVER['HTTP_HOST']. '/'. 'default')));
						$response->setStatusCode(302);
						$response->sendHeaders();
						$event->stopPropagation();
					}
				}, -100);
				
			return $response;
		}
		else
		{
			/**
			 * @access Initialize the Acl
			 * @return resource
			 */
			
			$sharedManager = $eventManager->getSharedManager();
			$sharedManager->attach('Zend\Mvc\Controller\AbstractActionController',
			'dispatch', function ($event) use 
								 ($serviceManager, $authenticationServiceStorage, $serviceAdministratorConfigManager) 
			{
				$serviceManager->get('ControllerPluginManager')->get('AdministratorAclPlugin')
							   ->initialAclRole($event, $serviceAdministratorConfigManager, 
							   					$authenticationServiceStorage);
			});
		}
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				)
			)
		);
	}
}