<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Controller\Plugins;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class AclPlugin extends AbstractPlugin
{
	public function __construct() {}
	
	public function initialAclRole($e, $serviceAdministratorConfigManager, $authenticationServiceStorage)
	{
		$oAcl = new Acl();
		
		$oAcl->deny();
		
		$oAcl->addRole(new Role('staff_1'));
		$oAcl->addRole(new Role('staff_2'));
		$oAcl->addRole(new Role('administrator'));
		
		$oAcl->addResource('administrator');
		$oAcl->addResource('api');
		
		$oAcl->allow('staff_1','administrator','index:index');
		$oAcl->allow('staff_1','administrator','user:profile');
		$oAcl->allow('staff_1','administrator','user:list');
		$oAcl->allow('staff_1','administrator','menu:list');
		
		$controllerClass = get_class($e->getTarget());
		$moduleName = strtolower(substr($controllerClass, 0, strpos($controllerClass, '\\')));
		$routeMatch = $e->getRouteMatch();
		$aName = strtolower($routeMatch->getParam('action', 'not-found')); 
		$cName = strtolower($routeMatch->getParam('__CONTROLLER__', 'not-found'));
		
		/*
		if (!$oAcl->isAllowed("staff_1",$moduleName, "{$cName}:{$aName}"))
		{
			$response = $e->getResponse();
			$response->setStatusCode(302);
			$response->getHeaders()->addHeaderLine('Location', $e->getRouter()->assemble($serviceAdministratorConfigManager['options']['constraints'], 
					array('name' => $_SERVER['HTTP_HOST']. '/'. 'default')));
			$e->stopPropagation();
		}
		*/
	}
}






