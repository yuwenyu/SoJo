<?php 
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Administrator\Service\UserServiceInterface;
use Administrator\Service\CoreServiceInterface;
use Administrator\Model\AdministratorModelInterface;

class UserController extends AbstractActionController
{
	protected $cUser;
	protected $cCore;
	protected $cMAdministrator;
	
	public function __construct(AdministratorModelInterface $oAdministrator,UserServiceInterface $oUser, 
								CoreServiceInterface $oCore)
	{	
		$this->cUser = $oUser;
		$this->cCore = $oCore;
		$this->cMAdministrator = $oAdministrator;
	}
	
	public function listAction() 
	{
		$vStaticParameters = $this->cCore->ArrayToIterator(
		array(
			'vStaticTitle' => array('p'=>'USER','c'=>'LIST_FIELDS_TITLE')
		));
		return compact('vStaticParameters');
	}
	
	public function dataAction()
	{
		$page = 0;
		$rows = 0;
		
		if ($this->getRequest()->isPost())
		{
			$page = $this->getRequest()->getPost('page');
			$rows = $this->getRequest()->getPost('rows');
		}
		
		echo $this->cMAdministrator->getPagination($page, $rows);
		exit();
	}
	
	public function loginAction()
	{
		if ($this->cUser->hasAuthStorage()) 
		{
			$this->redirect()->toRoute($_SERVER['HTTP_HOST'] . '/' . 'panel',
				$this->cCore->getToRouteConfiguration());
		}
		
		$this->layout('layout/login');
	}
	
	public function quitAction()
	{
		$this->cUser->setQuit();
		$this->redirect()->toRoute($_SERVER['HTTP_HOST'] . '/' . 'default',
			$this->cCore->getToRouteConfiguration(array('controller'=>'User','action'=>'login')));
	}
	
	public function profileAction()
	{
		$administratorUserAuthStorage = $this->cUser->getAuthStorage();
		return compact('administratorUserAuthStorage');
	}
}