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

class ApiController extends AbstractActionController
{
	protected $cUser;
	
	public function __construct(UserServiceInterface $oUser)
	{
		$this->cUser = $oUser;	
	}
	
	public function ajaxIsUserLoginAction()
	{
		echo json_encode(array('code'=>$this->cUser->hasAuthStorage()?1:-1));
		exit();
	}
	
	public function ajaxUserLoginAction()
	{
		if ($this->getRequest()->isPost()) 
		{
			$pwdEncrypt = new \Zend\Crypt\Password\Apache();
			$pwdEncrypt->setFormat('sha1');
			$filterReplace = new \Zend\Filter\PregReplace(array('pattern'=>'/{SHA}/','replacement'=>''));
			$pwdEncryptResult = $filterReplace->filter(
					$pwdEncrypt->create($this->getRequest()->getPost('password')));
			
			$authService = $this->cUser
					->setUsername($this->getRequest()->getPost('username'))
					->setPassword($pwdEncryptResult)->authenticate();
			
			if ($authService['status'] === 'success')
			{
				echo json_encode(array('status'=>'success','code'=>$authService['code']));
			}
			else
			{
				echo json_encode(array('status'=>'failure','code'=>$authService['code']));
			}
		}
		else
		{
			echo json_encode(array('status'=>'failure','code'=>'401'));
		}
		exit();
	}
}